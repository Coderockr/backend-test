<?php
declare(strict_types=1);

namespace Api\Tests\Unit\Controllers;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Slim\Psr7\Response;

abstract class AbstractControllerTest extends TestCase
{
    protected function assertJsonResponse(
        MockObject|ResponseInterface $response,
        array $data,
        int $code = 200
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

        $jsonResponse->expects(static::once())
                     ->method('withStatus')
                     ->with($code)
                     ->willReturnSelf();

        return $jsonResponse;
    }

}