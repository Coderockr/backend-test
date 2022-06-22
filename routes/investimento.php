<?php

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->group('/investimento', function () use ($app) {
    $app->post('/', fn(Request $request, Response $response) => $this->ApiController->save($request, $response));
    $app->get('/{owner}/{index}/', fn(Request $request, Response $response) => $this->ApiController->viewInvestiment($request, $response));
    $app->get('/{id}/', fn(Request $request, Response $response) => $this->ApiController->viewInvestiment($request, $response));
    $app->put('/{id}/', fn(Request $request, Response $response) => $this->ApiController->withdraw($request, $response));
});