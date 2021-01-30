<?php
declare(strict_types=1);

namespace Api\Tests\Unit\Application;

use Api\Application\Controllers\AbstractController;
use Api\Application\Controllers\IndexController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class IndexControllerTest extends AbstractControllerTest
{
    protected function controller(): AbstractController
    {
        return new IndexController();
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