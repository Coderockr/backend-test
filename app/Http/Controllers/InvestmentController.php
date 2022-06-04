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
        $expectedBalance = (string) $investedService->getExpectedBalance($investment);

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

        $investedService = new InvestmentService();
        $expectedBalance = $investedService->getInvestmentReturn($investment);
        
        return response($expectedBalance, 201);
    }
}
