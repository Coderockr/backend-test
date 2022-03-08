<?php

namespace App\Http\Controllers;

use App\Investment;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{

    public function showAllInvestments()
    {
        return response()->json(Investment::all());
    }


    public function createInvestment(Request $request)
    {

        $this->validate($request, [
            'investor_id' => 'required|exists:investors,id',
            'date_creation' => 'required|date|before_or_equal:today|date_format:Y-m-d',
            'amount_start' => 'required|gt:0|regex:/^\d+(\.\d{1,2})?$/',
            'date_withdraw' => 'prohibited',
            'withdrawn' => 'prohibited',
            'amount_total' => 'prohibited',
            'gain' => 'prohibited',
            'tax' => 'prohibited'
        ]);

        $investment = Investment::create($request->all());

        return response()->json($investment, 201);
    }


}