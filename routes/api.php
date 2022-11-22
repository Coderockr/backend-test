<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\InvestmentMovementController;
use App\Http\Controllers\InvestmentWithdrawnController;
use App\Http\Controllers\PersonInvestmentsController;

Route::prefix('v1')->group(function() {
    Route::resource('persons', PersonController::class, ['except' => ['create', 'edit']]);
    Route::get('persons/{id}/investments', [PersonInvestmentsController::class, 'index']);

    Route::resource('investments', InvestmentController::class, ['except' => ['create', 'edit']]);
    Route::get('investments/{id}/movements', [InvestmentMovementController::class, 'index'])
        ->name('investments.movements.index');

    Route::patch('investments/{id}/withdrawn', [InvestmentWithdrawnController::class, 'withdrawn']);
});