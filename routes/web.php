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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->post('investors', ['uses' => 'InvestorController@createInvestor']);
    $router->get('investors',  ['uses' => 'InvestorController@showAllInvestors']);
    $router->get('investors/{id}', ['uses' => 'InvestorController@showOneInvestor']);
    $router->put('investors/{id}', ['uses' => 'InvestorController@updateInvestor']);

    $router->post('investments', ['uses' => 'InvestmentController@createInvestment']);
    $router->get('investments', ['uses' => 'InvestmentController@showAllInvestments']);
    $router->get('investments/{id}', ['uses' => 'InvestmentController@showOneInvestment']);

});