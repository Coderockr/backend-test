<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvestmentMovementRequest;
use App\Http\Requests\UpdateInvestmentMovementRequest;
use App\Models\InvestmentMovement;

class InvestmentMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreInvestmentMovementRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvestmentMovementRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvestmentMovement  $investmentMovement
     * @return \Illuminate\Http\Response
     */
    public function show(InvestmentMovement $investmentMovement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvestmentMovement  $investmentMovement
     * @return \Illuminate\Http\Response
     */
    public function edit(InvestmentMovement $investmentMovement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInvestmentMovementRequest  $request
     * @param  \App\Models\InvestmentMovement  $investmentMovement
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvestmentMovementRequest $request, InvestmentMovement $investmentMovement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvestmentMovement  $investmentMovement
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvestmentMovement $investmentMovement)
    {
        //
    }
}
