<?php

use App\Http\Response;
use App\Controller\Api\Api;
use App\Controller\Api\BasicAuth;
use App\Controller\Api\Investment;



$router->get(
    '/',
    [
        function($request) 
        {
            return new Response(200, Api::getDetails($request));
        }
    ]
);

$router->post(
    '/api/v1/auth',
    [
        function($request)
        {
            return new Response(200, BasicAuth::basicAuth($request));
        }
    ]
);

$router->get(
    '/api/v1/investment',
    [
        function($request) 
        {
            return new Response(200, Investment::getInvestmentList($request));
        }
    ]
);

$router->post(
    '/api/v1/investment',
    [
        function($request) 
        {
            return new Response(201, Investment::setNewInvestment($request));
        }
    ]
);

$router->get(
    '/api/v1/investment/{id}',
    [
        function($request, $id) 
        {
            return new Response(200, Investment::getInvestmentOverview($request, $id));
        }
    ]
);