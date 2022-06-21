<?php

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

$app->group('/investimento', function () use ($app) {
    $app->post('/', fn(Request $request, Response $response) => $this->ApiController->save($request, $response));
});