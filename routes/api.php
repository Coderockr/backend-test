<?php

use App\Http\Controllers\PersonController;
use App\Http\Controllers\InvestmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    Route::controller(PersonController::class)->group(function () {
        Route::get('/persons', 'index');
        Route::get('/person/{id}', 'show');
        Route::get('/person/{id}/investments', 'investments');
        Route::post('/person', 'store');
    });

    Route::controller(InvestmentController::class)->group(function () {
        Route::get('/investments', 'index');
        Route::post('/investment', 'store');
        Route::get('/investment/{id}/', 'show');
        Route::get('/investment/{id}/movements', 'movements');
        Route::get('/investment/{id}/withdraw', 'withdraw');
    });
});
