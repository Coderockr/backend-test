<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Investment;
use Illuminate\Http\Request;
use App\Enums\InvestmentStatus;
use App\Http\Requests\InvestmentStoreRequest;
use App\Services\InvestmentService;

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

            $investmentDate = Carbon::createFromDate($investment->investment_date);
            $withdrawalDate = Carbon::createFromDate($request->input('withdrawal_date'));

            if(!$request->input('withdrawal_date'))
                throw new \Exception("You must fill in a withdrawal date");

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
            return response()->json(['message' => $e->getMessage()], 400);
        }

        $investment->owner;

        return response()->json([
            'message' => 'Full withdrawal of investment successfully!',
            'investment' => $investment
            ], 200);
    }

    public function updateAllInvestments()
    {
        $investments = Investment::where('status', InvestmentStatus::ACTIVE)->where('expected_balance', 0);
        $count = $investments->count();

        foreach ($investments->get() as $investment) {
            try {
                $investmentService = new InvestmentService($investment);
                $investment->expected_balance = $investmentService->calculateGain();
                $investment->save();
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => "Update performed successfully! Count: {$count}"], 200);
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
            return response()->json(['message'=> $e->getMessage()], 422);
        }

        $investment = Investment::find($investment->id);
        $investment->owner;

        return response()->json($investment, 201);
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create(){}

    /**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
