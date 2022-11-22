<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\InvestmentMovementController;
use App\Http\Controllers\InvestmentWithdrawnController;

Route::prefix('v1')->group(function() {
    Route::resource('persons', PersonController::class, ['except' => ['create', 'edit']]);
    Route::resource('investments', InvestmentController::class, ['except' => ['create', 'edit']]);
    Route::get('investments/{id}/movements', [InvestmentMovementController::class, 'index']);
    Route::patch('investments/{id}/withdrawn', [InvestmentWithdrawnController::class, 'withdrawn']);
});