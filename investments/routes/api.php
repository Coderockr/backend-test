<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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


// Cria o JWT token
$router->post('/auth/login', 'AuthController@login');

// ACCESS THROUGH JWT TOKENS
$router->group(['middleware' => 'auth:web', 'prefix' => 'api/v1'], function () use ($router) {
    $router->get('/list', 'UserController@list');
});

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' =>'user'], function () use ($router) {
    // $router->get('/list', 'UserController@list');
});

$router->group(['prefix' =>'investment'], function () use ($router) {
    $router->post('/create', 'InvestmentController@create');
    
});
