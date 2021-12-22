<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvestmentController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/create',      [InvestmentController::class, 'create']);
Route::get('/view ',        [InvestmentController::class, 'view']);
Route::post('/withdrawal ', [InvestmentController::class, 'withdrawal']);
Route::get('/list',         [InvestmentController::class, 'list']);