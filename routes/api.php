<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::group(['middleware' => 'api', 'prefix' => 'auth'], function($router) {
//    Route::post('login', 'AuthController@login');
//    Route::post('logout', 'AuthController@logout');
//    Route::post('refresh', 'AuthController@refresh');
//    Route::post('me', 'AuthController@me');
//});

Route::post('auth/login', 'Api\\AuthController@login');
Route::post('auth/logout', 'Api\\AuthController@logout');
Route::post('auth/refresh', 'Api\\AuthController@refresh');
Route::post('auth/me', 'Api\\AuthController@me');

Route::post('user/store', 'Api\\UserController@store');

Route::group(['middleware' => ['apiJwt']], function () {
    Route::get('users', 'Api\\UserController@index');
});