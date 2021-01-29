<?php
declare(strict_types=1);

namespace Api\Tests\Unit\Application;

use Api\Application\AbstractController;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Response;

abstract class AbstractControllerTest extends TestCase
{
    protected ?AbstractController $controller;


    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->controller();
    }


    protected function controller(): ?AbstractController
    {
        return null;
    }


    protected function assertJsonResponse(
        MockObject|ResponseInterface $response,
        array $data
    ): MockObject|ResponseInterface
    {
        $body = $this->createMock(StreamInterface::class);

        $response->expects(static::once())
                 ->method('getBody')
                 ->willReturn($body);

        $body->expects(static::once())
             ->method('write')
             ->with(json_encode($data));

        $jsonResponse = $this->createMock(Response::class);

        $response->expects(static::once())
                 ->method('withHeader')
                 ->with('Content-Type', 'application/json')
                 ->willReturn($jsonResponse);

        return $jsonResponse;
    }

}