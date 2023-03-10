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

$router->group(['prefix' => 'v1'], function () use ($router) {

    $router->group(['prefix' => 'investiment'], function () use ($router) {
        $router->get('/{investor_id}', '\App\Http\Controllers\InvestmentController@getInvestmentsFromInvestor');
        $router->post('/{investor_id}/create', '\App\Http\Controllers\InvestmentController@createInvestment');
        $router->post('/{investor_id}/create', '\App\Http\Controllers\InvestmentController@createInvestment');
        $router->post('/{investor_id}/withdraw-investment', '\App\Http\Controllers\InvestmentController@withdrawInvestment');
        $router->get('/{investor_id}/investment/{id}', '\App\Http\Controllers\InvestmentController@getAInvestmentFromInvestor');
    });
});
