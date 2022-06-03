<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\User;

use Illuminate\Http\Request;

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
        //
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
