<?php

use App\Http\Controllers\PersonController;
use App\Http\Controllers\InvestmentController;
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

Route::prefix('/v1')->group(function () {
    Route::controller(PersonController::class)->group(function () {
        Route::get('/persons', 'index');
        Route::get('/person/{id}', 'show');
        Route::post('/person', 'store');
    });


});
