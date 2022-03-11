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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Create new investment
Route::post('investment', [InvestmentController::class, 'creation']);

// list all investments
Route::get('investments', [InvestmentController::class, 'index']);

// list a single investment by id
Route::get('investment/{id}', [InvestmentController::class, 'view']);

// List single withdrawal investment 
Route::get('withdrawal/{id}', [InvestmentController::class, 'withdrawal']);

// List all investment of an owner
Route::get('list/investments/{owner}', [InvestmentController::class, 'list']);