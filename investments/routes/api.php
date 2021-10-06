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

    $router->post('/investment/create', 'InvestmentController@create');
    $router->post('/investment/withdrawal', 'InvestmentController@withdrawal');
    $router->get('/investment/view/{investment_id}', 'InvestmentController@view');
    $router->get('/investment/user/{owner_id}', 'InvestmentController@viewInvestments');
    
    $router->post('/user/create', 'UserController@create');
    $router->get('/user/all/view', 'UserController@view');

});

$router->get('/', function () use ($router) {
    return $router->app->version();
});


