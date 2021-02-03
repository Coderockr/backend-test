<?php
declare(strict_types=1);

namespace Api\Tests\Unit\Controllers;

use Api\Controllers\IndexController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class IndexControllerTest extends AbstractControllerTest
{
    private IndexController $controller;


    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new IndexController(null, null);
    }


    public function testIndex(): void
    {
        $request  = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $data     = [
            'message' => 'Welcome to the Event Manager API',
        ];

        $jsonResponse = $this->assertJsonResponse($response, $data);

        static::assertSame($jsonResponse, $this->controller->index($request, $response));
    }

}