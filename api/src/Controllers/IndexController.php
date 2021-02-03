<?php
declare(strict_types=1);

namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class IndexController extends AbstractController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->json(
            $response,
            [
                'message' => 'Welcome to the Event Manager API',
            ]
        );
    }

}