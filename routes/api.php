<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::apiResource('user', \App\Http\Controllers\UserController::class);

Route::post('/investment/create', [\App\Http\Controllers\InvestmentController::class, 'store']);
Route::put('/investment/withdrawal/{id}', [\App\Http\Controllers\InvestmentController::class, 'withdrawal']);
Route::get('/investment/{id}', [\App\Http\Controllers\InvestmentController::class, 'show']);
Route::get('/investments/{id}', [\App\Http\Controllers\InvestmentController::class, 'list']);

