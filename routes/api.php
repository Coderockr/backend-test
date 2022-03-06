<?php

use App\Http\Response;
use App\Controller\Api\Investment;


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
            return new Response(200, Investment::setNewInvestment($request));
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