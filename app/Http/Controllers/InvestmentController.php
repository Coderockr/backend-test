<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\User;

use Illuminate\Http\Request;

use Carbon\Carbon;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $investments = auth()->user()->investments()->paginate(10);

        return response($investments, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request) {
        // Validating the request
        $fields = $request->validate([
            'amount' => 'required|numeric|between:0,999999.99',
            'inserted_at' => 'required|date|before_or_equal:today|date_format:Y-m-d'
        ]);

        // Creating the investment with the validated data through the relationship
        $investment = auth()->user()->investments()->create($fields);
        
        $response = [
            'investment' => $investment
        ];

        // Returning the response with the corrected status
        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Find for the user investment
        $investment = auth()->user()->investments()->find($id);
        
        $investedAmount = $investment['amount'];

        // Calculating how many times the gain fee was applied
        $insertedAt = Carbon::createFromFormat('Y-m-d', $investment['inserted_at'])->hour(0)->minute(0)->second(0);
        $currentDate = Carbon::now()->hour(0)->minute(0)->second(0);
        $numberOfMonths = $insertedAt->diffInMonths($currentDate);

        // Gain fees converted to decimal to use the compound gain formula
        $gainFees = 0.0052;

        // Casting to string to not use it in response as a float
        // Applying the M = C * (1 + i)^n formula
        $expectedBalance = (string) ($investedAmount * pow((1 + $gainFees), $numberOfMonths));

        $response = [
            'initial_amount' => $investedAmount,
            'expected_balance' => $expectedBalance
        ];

        return response($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
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
