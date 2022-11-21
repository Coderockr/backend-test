<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\InvestmentController;

Route::prefix('v1')->group(function() {
    Route::resource('persons', PersonController::class, ['except' => ['create', 'edit']]);
    Route::resource('investments', InvestmentController::class, ['except' => ['create', 'edit']]);
});