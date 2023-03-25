<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\OwnerController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('owners',OwnerController::class);

Route::resource('investments',InvestmentController::class);
