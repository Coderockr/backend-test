<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Investment;
use Illuminate\Http\Request;
use App\Enums\InvestmentStatus;
use App\Services\InvestmentService;
use App\Http\Requests\InvestmentStoreRequest;
use Symfony\Component\HttpFoundation\Response;

class InvestmentController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Investments"},
     *     path="/api/investments",
     *     summary="Returns all investments",
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     *
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $investments = Investment::with('owner')
        ->paginate(10);

        return response()->json($investments);
    }

    /**
     * @OA\Post(
     *     tags={"Investments"},
     *     path="/api/investments/withdraw/{id}",
     *     summary="Withdrawal of investment",
     *    @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="withdrawal_date",
     *                     type="date",
     *                 ),
     *                 example={"withdrawal_date": "2022-12-10"}
     *             )
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Investment uuid",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="uuid", value="98c660e0-a22e-4c91-b4c6-65737720228d", summary="An UUID value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     *
     * @param Investment $investment
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function withdraw(Investment $investment, Request $request)
    {
        try {
            if($investment->status != InvestmentStatus::ACTIVE)
                throw new \Exception("Investment {$investment->id} has already been withdrawn");

            if(!$request->input('withdrawal_date'))
                throw new \Exception("You must fill in a withdrawal date");

            $investmentDate = Carbon::createFromDate($investment->investment_date);
            $withdrawalDate = Carbon::createFromDate($request->input('withdrawal_date'));

            if($investmentDate->gt($withdrawalDate) || $withdrawalDate->gt(Carbon::now()))
                throw new \Exception("Withdrawals can happen in the past or today, but cannot happen before investment creation or in the future");

            $investmentService = new InvestmentService($investment);
            $GainWithTaxDeductions = $investmentService->applyRate();

            $onlyGain = $investment->expected_balance - $investment->invested_amount;

            $investment->taxes = round(($onlyGain - $GainWithTaxDeductions),2);
            $investment->withdrawn_amount = round(($investment->invested_amount + $GainWithTaxDeductions),2);
            $investment->withdrawal_date = $request->input('withdrawal_date');

            $investment->status = InvestmentStatus::WITHDRAWN;
            $investment->expected_balance = round($onlyGain, 2);
            $investment->save();

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $investment->owner;

        return response()->json([
            'message' => 'Full withdrawal of investment successfully!',
            'investment' => $investment
            ], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     tags={"Investments"},
     *     path="/api/investments/{id}",
     *     summary="Returns information about the investment and his owner",
     *     @OA\Parameter(
     *         description="Investment uuid",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="uuid", value="98c660e0-a22e-4c91-b4c6-65737720228d", summary="An UUID value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     *
     * @param Investment $id
     * @return \Illuminate\Http\Response
     */
    public function show(Investment $investment)
    {
        $investment->owner;
        return response()->json($investment);
    }

    /**
     * @OA\Post(
     *     tags={"Investments"},
     *     path="/api/investments",
     *     summary="Create an investment",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="investment_date",
     *                     type="date"
     *                 ),
     *                 @OA\Property(
     *                     property="invested_amount",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="owner_id",
     *                     type="string"
     *                 ),
     *                 example={"investment_date": "2022-01-02", "invested_amount": "2200.15", "owner_id": "98c6afc6-bfdb-4ef8-ab80-daa7dc8aa440"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="OK"
     *     )
     * )
     *
     * @param InvestmentStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvestmentStoreRequest $request)
    {
        try {
            $investment = Investment::create($request->all());
        } catch (\Exception $e) {
            return response()->json(['message'=> $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $investment = Investment::find($investment->id);
        $investment->owner;

        return response()->json($investment, Response::HTTP_CREATED);
    }

    /**
     * @OA\Delete(
     *     tags={"Investments"},
     *     path="/api/investments/{id}",
     *     summary="Destroy an investment",
     *     @OA\Parameter(
     *         description="Investment uuid",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="uuid", value="98c660e0-a1ad-41b6-9695-8f34476e0666", summary="An UUID value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     *
     * @param Investment $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Investment $investment)
    {
        $investment->delete();
        return response()->json(['message'=> 'Investment successfully removed!']);
    }

}
