<?php

use App\Http\Controllers\InvestidorController;
use App\Http\Controllers\InvestimentoController;
use App\Http\Controllers\SaqueController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('investidor')->group(function () {
    Route::post('/', [InvestidorController::class, 'create']);
});

Route::prefix('investimento')->group(function () {
    Route::post('/', [InvestimentoController::class, 'create']);
    Route::get('/ganho',[InvestimentoController::class, 'ganhar']);
    Route::post('/sacar', [SaqueController::class, 'sacar']);
    Route::get('/{investidor}', [InvestimentoController::class, 'show']);
});
