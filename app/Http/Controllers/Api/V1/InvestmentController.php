<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Http\Requests\StoreInvestmentRequest;

use App\Services\InvestmentService;
use App\Models\Investment;

use Carbon\Carbon;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index() { //}: LengthAwarePaginator {
        $investments = auth()->user()->investments()->paginate(10);

        return $investments;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreInvestmentRequest  $request
     * @return \App\Models\Investment;
     */
    public function store(StoreInvestmentRequest $request) : Investment {
        // Creating the investment with the validated data through the relationship
        $investment = auth()->user()->investments()->create($request->all());
        
        return $investment;
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

        // If doesnt find the investment
        if(!$investment) {
            return response([
                'message' => 'Invesment doenst exist or already was withdrawaled'
            ], 404);    
        }

        $investedService = new InvestmentService();
        $expectedBalance = $investedService->getExpectedBalance($investment, Investment::MONTH_GAIN_VALUE, Carbon::now());

        $response = [
            'initial_amount' => $investment->amount,
            'expected_balance' => (string) $expectedBalance
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
        // Find for the user investment
        $investment = auth()->user()->investments()->find($id);

        // If doesnt find the investment
        if(!$investment) {
            return response([
                'message' => 'Invesment doenst exist or already was withdrawaled'
            ], 404);    
        }

        $investedAmount = $investment->amount;

        $investedService = new InvestmentService();
        $returnInvestmentValue = $investedService->getInvestmentReturn($investment, Investment::MONTH_GAIN_VALUE, Carbon::now());
        
        if(!$investment->delete()) {
            return response([
                'message' => 'Cannot withdrawal now, try again later.'
            ], 202);
        }

        $response = [
            'initial_amount' => $investedAmount,
            'return_value' => (string) $returnInvestmentValue
        ];

        return response($response, 201);
    }
}