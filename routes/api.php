<?php

use App\Http\Controllers\Api\InvestmentController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProfitController;
use App\Http\Controllers\Api\WithdrawController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', LoginController::class)->name('login');

Route::middleware('auth:api')->prefix('/investments')->group(function () {
    Route::get('/', [InvestmentController::class, 'index']);
    Route::post('/create', [InvestmentController::class, 'store']);
});

Route::middleware('auth:api')->prefix('/withdraws/{investmentId}')->group(function() {
   Route::get('/', [WithdrawController::class, 'index']);
   Route::post('/create', [WithdrawController::class, 'store']);
});

Route::middleware('auth:api')->prefix('/profits/{investmentId}')->group(function() {
   Route::get('/', [ProfitController::class, 'index']);
   Route::post('/create/', [ProfitController::class, 'store']);
});


