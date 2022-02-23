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

$router->group(['prefix' => 'api/v1/'], function () use ($router) {
    $router->group(['prefix' => 'investor'], function () use ($router) {
        $router->get('/', 'InvestorController@index');
        $router->post('/', 'InvestorController@store');
        $router->group(['prefix' => '/{investor_id}/investment'], function () use ($router) {
            $router->get('/', 'InvestmentController@index');
            $router->post('/', 'InvestmentController@store');
            $router->get('/{investment_id}', 'InvestmentController@see');
            $router->post('/{investment_id}', 'InvestmentController@makeInvestment');
            $router->delete('/{investment_id}', 'InvestmentController@withdrawInvestment');
        });
    });
});
