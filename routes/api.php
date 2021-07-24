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

Route::group(['prefix' => 'auth'], function($router) {
    Route::post('register', 'Api\\AuthController@register');
    Route::post('login', 'Api\\AuthController@login');
});

Route::group(['middleware' => ['apiJwt']], function($router) {
    Route::group(['prefix' => 'auth'], function($router) {
        Route::post('logout', 'Api\\AuthController@logout');
        Route::post('me', 'Api\\AuthController@me');
    });

    Route::group(['prefix' => 'my-events'], function($router) {
        Route::post('store', 'Api\\EventController@store');
        Route::get('/{id}/edit', 'Api\\EventController@show');
        Route::put('/{id}/update', 'Api\\EventController@update');
    });

});

Route::group(['prefix' => 'events'], function($router) {
    Route::get('/', 'Api\\PublicEventController@index');
    Route::get('/{id}', 'Api\\PublicEventController@show');
});

// Not Found
Route::fallback(function(){
    return response()->json(['status' => 404, 'message' => 'Resource not found.'], 404);
});