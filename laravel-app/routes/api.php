<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\OwnerController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Owners
Route::resource('owners',OwnerController::class);
Route::get('owners/only-investments/{owner}',[OwnerController::class,'onlyInvestments']);

//Investments
Route::resource('investments',InvestmentController::class);
Route::get('investments/withdraw/{investment}',[InvestmentController::class,'withdraw']);
