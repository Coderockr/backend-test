<?php
declare(strict_types=1);

namespace Api\Application;

use Psr\Http\Message\ResponseInterface;

abstract class AbstractController
{
    protected function json(ResponseInterface $response, array $data): ResponseInterface
    {
        $json = json_encode($data);
        $response->getBody()->write($json);

        return $response->withHeader('Content-Type', 'application/json');
    }

}