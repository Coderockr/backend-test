<?php


use App\Models\Owner;
use App\Http\Controllers\{InvestmentController, OwnerController, WithdrawalController};
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/owner', [OwnerController::class, 'create']);
Route::get('/owners_investments', [OwnerController::class, 'showInvestments']);
Route::post('/investment', [InvestmentController::class, 'create']);
Route::get('/investment', [InvestmentController::class, 'show']);
Route::post('/withdrawal', [InvestmentController::class, 'withdrawal']);