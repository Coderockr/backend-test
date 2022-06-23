<?php

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->group('/cliente', function () use ($app) {
    $app->post('/', fn(Request $request, Response $response) => $this->ClientController->create($request, $response));
});