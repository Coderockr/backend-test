<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiSwaggerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::fallback(function () {
    return response()->json([
        'status' => 'Error',
        'statuscode' => 404,
        'data' => [],
        'message' => 'Página não encontrada. Desculpe os transtornos. ' . url()->current()
    ], 404);
});

Route::get('/', function () {
    return view('welcome');
});
