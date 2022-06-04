<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvestmentRequest;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\InvestmentService;
use App\Models\Investment;

use Carbon\Carbon;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() : Response {
        $investments = auth()->user()->investments()->paginate(10);

        return response($investments, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreInvestmentRequest  $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreInvestmentRequest $request) : Response {
        // Creating the investment with the validated data through the relationship
        $investment = auth()->user()->investments()->create($request->all());
        
        // Returning the response with the corrected status
        return response($investment, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id) : Response {
        // Find for the user investment
        $investment = auth()->user()->investments()->find($id);

        $investedService = new InvestmentService();
        $expectedBalance = (string) $investedService->getCompoundGains($investment, Investment::GAIN_VALUE);

        $response = [
            'initial_amount' => $investment->amount,
            'expected_balance' => $expectedBalance
        ];

        return response($response, 200);
    }

    /**
     * Execute the withdrawal of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function withdrawal(int $id) : Response {
        $investment = auth()->user()->investments()->find($id);
        $investedAmount = $investment->amount;

        // Calculating how many times the gain fee was applied
        $insertedAt = Carbon::createFromFormat('Y-m-d', $investment->inserted_at)->hour(0)->minute(0)->second(0);
        $currentDate = Carbon::now()->hour(0)->minute(0)->second(0);
        $numberOfMonths = $insertedAt->diffInMonths($currentDate);

        // Gain fees converted to decimal to use the compound gain formula
        $gainFees = 0.0052;

        // Casting to string to not use it in response as a float
        $totalValue = $investedAmount * pow((1 + $gainFees), $numberOfMonths);

        // Only the gain value
        $gainValue =  $totalValue - $investedAmount;

        $numberOfYears = $insertedAt->diffInYears($currentDate);

        $taxPercent = match (true) {
            $numberOfYears < 1 => 0.225,
            $numberOfYears >= 1 && $numberOfYears < 2 => 0.185,
            $numberOfYears >= 2 => 0.15,
            default => 1
        };

        $moneyToReduce = $taxPercent * $gainValue;

        $totalValue -= $moneyToReduce;

        $response = [
            'total' => $totalValue,
            'taxPercent' => $taxPercent,
            'taxValue' => $moneyToReduce,
            'gain' => $gainValue,
            'initial' => $investedAmount
        ];
        /*
            (< 1 ano) =>
            If it is less than one year old, the percentage will be 22.5% (tax = 45.00).
            If it is between one and two years old, the percentage will be 18.5% (tax = 37.00).
            If older than two years, the percentage will be 15% (tax = 30.00).
        */
        return response($response, 201);
    }
}
