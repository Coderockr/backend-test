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
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $investments = Investment::with('owner')
        ->paginate(10);

        return response()->json($investments);
    }

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
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Investment $investment)
    {
        $investment->owner;
        return response()->json($investment);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
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
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Investment $investment)
    {
        $investment->delete();
        return response()->json(['message'=> 'Investment successfully removed!']);
    }

}
