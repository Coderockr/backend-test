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



Route::prefix('v1')->middleware('logAPIInputAndOutput')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::post('login', [\App\Http\Controllers\Admin::class, 'Login']);
    });
    
    Route::prefix('investments')->middleware('filterNonAuthenticatedAPIRequests')->group(function () {
        Route::prefix('user')->group(function () {

            Route::put('create', [\App\Http\Controllers\Investments\User::class, 'Create']);
            Route::post('investments/list', [\App\Http\Controllers\Investments\User::class, 'ListInvestments']);
        });
        
        Route::put('create', [\App\Http\Controllers\Investments::class, 'Create']);
        Route::post('view', [\App\Http\Controllers\Investments::class, 'View']);
        Route::post('withdrawal', [\App\Http\Controllers\Investments::class, 'Withdrawal']);
    });
    
});
