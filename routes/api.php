<?php

use App\Http\Controllers\Api\ApiSwaggerController;
use App\Http\Controllers\Api\v1\InvestimentoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::group(['prefix' => 'v1'], function () { // 'middleware' => ['auth:sanctum']

    Route::apiResource('investimento', InvestimentoController::class)->except(['index', 'show', 'update', 'destroy']);
    Route::get('/investimento/{investimento}/{investidor}/visualizar', [InvestimentoController::class, 'showInvestimentoInvestidor'])->name('investimento.visualizar');
    Route::get('/investimento/{investidor}/investimentos', [InvestimentoController::class, 'showInvestimentosInvestidor'])->name('investimento.investidor.investimentos');
    Route::get('/investimento/{investimento}/{data_retirada}/{investidor}/retirar/simulacao', [InvestimentoController::class, 'simularRetiradaInvestimentoInvestidor'])->name('investimento.simular_retirada_investimento');
    Route::post('/investimento/retirar', [InvestimentoController::class, 'retiradaInvestimentoInvestidor'])->name('investimento.retirada_investimento');
});

Route::get('/swagger/docs', [ApiSwaggerController::class, 'getSwagger'])->name('api.swagger');
