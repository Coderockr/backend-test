<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestmentCreateRequest;
use App\Http\Requests\InvestmentWithdrawalRequest;
use App\Models\Investment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InvestmentController extends Controller
{

    private $earn_rate = 0.52;

    private function calculateInvestmentMonths($investment, $endDate)
    {
        $inicialDate = new \DateTime($investment->investment_date);
        $endDate = new \DateTime(date("Y-m-d"));

        $interval = $inicialDate->diff($endDate);

        return (int) (($interval->format('%y') * 12) + $interval->format('%m') + 1);

    }

    private function calculateEarnings($investment, $endDate)
    {
        $months = $this->calculateInvestmentMonths($investment, $endDate);

        $earning = $investment->investment_amount;
        for ($i = 1; $i <= $months; $i++) {
            $earning = $earning + (($earning * $this->earn_rate) / 100);
        }

        return $earning - $investment->investment_amount;
    }

    private function calculateTaxes($investment, $endDate)
    {
        $months = $this->calculateInvestmentMonths($investment, $endDate);
        $earnings = $this->calculateEarnings($investment, $endDate);
        $amountTaxes = ($earnings / $investment->investment_amount);
        $taxes = $this->checkTaxes($months);

        return (($amountTaxes * $taxes) / 100);
    }

    private function checkTaxes($months)
    {
        switch (true) {
            case $months > 24 :
                $taxes = 15;
                break;
            case $months >= 12 :
                $taxes = 18.5;
                break;
            default :
                $taxes = 22.5;
                break;
        }

        return $taxes;
    }

    /**
     * @OA\Post(
     * path="/api/investment",
     * summary="Creation of an investment",
     * description="Creation of an investment with an owner, a creation date and an amount",
     *     @OA\RequestBody(
     *          @OA\JsonContent( @OA\Property(property="user_id", type="int", example="1", description="User ID"),
     *          @OA\Property(property="amount", type="double", example="1000.00", description="Initial amount of investment"),
     *          @OA\Property(property="investment_date", type="double", example="1000.00", description="Initial date of investment"),
     *          ),
     *
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store(InvestmentCreateRequest $request): JsonResponse
    {
        $user = Investment::create($request->all());

        return response()->json($user, 201);
    }

    /**
     * @OA\Get (
     * path="/api/investment/:id",
     * summary="View of an investment",
     * description="View of an investment with its initial amount and expected balance",
     *

     *     @OA\RequestBody(
     *          @OA\JsonContent( @OA\Property(property="end_date", type="date", example="2021-12-21", description="Used to simlulate an expected balance"),
     *          ),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show(Request $request, $id): JsonResponse
    {
        $endDate = $request->end_date ?? date("Y-m-d");

        $investment = Investment::findOrFail($id);

        $earnings = $this->calculateEarnings($investment, $endDate);

        $investment->expected_balance = $investment->investment_amount + $earnings;

        return response()->json($investment, 200);
    }

    /**
     * @OA\Put (
     * path="/api/investment/withdrawal/:id",
     * summary="Withdrawal of a investment",
     * description="Withdrawal of a investment",
     *     @OA\RequestBody(
     *          @OA\JsonContent( @OA\Property(property="withdrawal_date", type="date", example="2021-12-21", description="Used to inform a withdrawal date"),
     *          @OA\Property(property="id", type="int", example="1", description="Investment ID"),
     *          ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Withdrawal Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function withdrawal(InvestmentWithdrawalRequest $request, $id): JsonResponse
    {
        $investment = Investment::findOrFail($id);

        if($investment->withdrawal_date){
            //throw ValidationException::withMessages(['error'=>'This investment has already been completed']);
        }

        $earnings = $this->calculateEarnings($investment, $request->end_date);
        $investment->taxes = $this->calculateTaxes($investment, $request->end_date);
        $investment->withdrawal_date = $request->end_date;
        $investment->withdrawal_amount = number_format($investment->investment_amount + $earnings - $investment->taxes, 2, '.', '');

        //dd($earnings);

        $investment->save();

        return response()->json($investment, 200);
    }

    /**
     * @OA\Get  (
     * path="/api/investments/:id",
     * summary="List of a person's investments",
     * description="List of a person's investments",
     *      @OA\Response(
     *          response=200,
     *          description="Ok",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function list($id): JsonResponse
    {
        $user = User::with([
            'investments'
        ])->findOrFail($id);

        return response()->json($user, 200);
    }


}
