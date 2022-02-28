<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');


// ROUTES WITH AUTH
Route::group(['middleware' => 'auth:api'], function(){
    
    // Route::get('user', [ 'as' => 'user', 'uses' => 'UserController@user' ]);
    
    Route::group([
        'prefix' => 'investment',
        'as' => 'investment'
    ], function(){

        Route::post('/', [ 
            'as' => 'store', 
            'uses' => 'InvestmentController@store' 
        ]);
        
    });
});

