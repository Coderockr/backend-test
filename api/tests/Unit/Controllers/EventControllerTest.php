<?php
declare(strict_types=1);

namespace Api\Tests\Unit\Controllers;

use Api\Controllers\EventController;
use Api\Filters\EventFilter;
use Api\Tables\EventTable;
use Laminas\Db\Adapter\Driver\Mysqli\Result;
use Laminas\Paginator\Paginator;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouteInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Psr7\Uri;
use Slim\Routing\RouteContext;
use Slim\Routing\RoutingResults;

final class EventControllerTest extends AbstractControllerTest
{
    private EventTable|MockObject $table;

    private MockObject|EventFilter $filter;

    private EventController $controller;


    protected function setUp(): void
    {
        parent::setUp();

        $this->table  = $this->createMock(EventTable::class);
        $this->filter = $this->createMock(EventFilter::class);

        $this->controller = new EventController($this->table, $this->filter);
    }


    public function testListWithPagination(): void
    {
        $request  = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->filter->expects(static::once())
                     ->method('addFilters')
                     ->with($request);

        $result = $this->createMock(Paginator::class);

        $this->table->expects(static::once())
                    ->method('fetchAll')
                    ->with($this->filter)
                    ->willReturn($result);

        $iterator = new \ArrayIterator(
            [
                [
                    'event_id'   => 1,
                    'event_name' => 'Event 1',
                ],
            ]
        );

        $result->expects(static::once())
               ->method('getIterator')
               ->willReturn($iterator);

        $request->expects(static::once())
                ->method('getQueryParams')
                ->willReturn(['page' => 1]);

        $result->expects(static::once())
               ->method('setCurrentPageNumber')
               ->with(1);

        $pagesObj                   = new \stdClass;
        $pagesObj->next             = 2;
        $pagesObj->current          = 1;
        $pagesObj->itemCountPerPage = 10;
        $pagesObj->pageCount        = 1;

        $result->expects(static::once())
               ->method('getPages')
               ->willReturn($pagesObj);

        $uri = $this->createMock(Uri::class);

        $request->expects(static::once())
                ->method('getUri')
                ->willReturn($uri);

        $uri->expects(static::once())
            ->method('getPath')
            ->willReturn('/api');

        $data = [
            'status'     => 'OK',
            'pagination' => [
                'previous_page'      => null,
                'next_page'          => 2,
                'current_page'       => 1,
                'items_per_page'     => 10,
                'page_count'         => 1,
                'previous_page_link' => null,
                'next_page_link'     => '/api?page=2',
            ],
            'data'       => [
                [
                    'event_id'   => 1,
                    'event_name' => 'Event 1',
                ],
            ],
        ];

        $jsonResponse = $this->assertJsonResponse($response, $data);

        static::assertSame($jsonResponse, $this->controller->list($request, $response));
    }


    public function testListWithPaginationAndNoPageNumber(): void
    {
        $request  = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->filter->expects(static::once())
                     ->method('addFilters')
                     ->with($request);

        $result = $this->createMock(Paginator::class);

        $this->table->expects(static::once())
                    ->method('fetchAll')
                    ->with($this->filter)
                    ->willReturn($result);

        $iterator = new \ArrayIterator(
            [
                [
                    'event_id'   => 1,
                    'event_name' => 'Event 1',
                ],
            ]
        );

        $result->expects(static::once())
               ->method('getIterator')
               ->willReturn($iterator);

        $request->expects(static::once())
                ->method('getQueryParams')
                ->willReturn([]);

        $result->expects(static::never())->method('setCurrentPageNumber');
        $result->expects(static::never())->method('getPages');
        $request->expects(static::never())->method('getUri');

        $data = [
            'status'     => 'OK',
            'pagination' => [],
            'data'       => [
                [
                    'event_id'   => 1,
                    'event_name' => 'Event 1',
                ],
            ],
        ];

        $jsonResponse = $this->assertJsonResponse($response, $data);

        static::assertSame($jsonResponse, $this->controller->list($request, $response));
    }


    public function testListWithoutPagination(): void
    {
        $request  = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->filter->expects(static::once())
                     ->method('addFilters')
                     ->with($request);

        $result = $this->createMock(Result::class);

        $this->table->expects(static::once())
                    ->method('fetchAll')
                    ->with($this->filter)
                    ->willReturn($result);

        $request->expects(static::never())->method('getQueryParams');
        $request->expects(static::never())->method('getUri');

        $data = [
            'status'     => 'OK',
            'pagination' => [],
            'data'       => [],
        ];

        $jsonResponse = $this->assertJsonResponse($response, $data);

        static::assertSame($jsonResponse, $this->controller->list($request, $response));
    }


    public function testDetailsWithIdNotFound(): void
    {
        $request  = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $route          = $this->createMock(RouteInterface::class);
        $routeParser    = $this->createMock(RouteParserInterface::class);
        $routingResults = $this->createMock(RoutingResults::class);

        $request->expects(static::exactly(4))
                ->method('getAttribute')
                ->withConsecutive(
                    [RouteContext::ROUTE],
                    [RouteContext::ROUTE_PARSER],
                    [RouteContext::ROUTING_RESULTS],
                    [RouteContext::BASE_PATH]
                )
                ->willReturn(
                    $route,
                    $routeParser,
                    $routingResults,
                    null,
                    null
                );

        $route->expects(static::once())
              ->method('getArgument')
              ->with('id')
              ->willReturn('1');

        $event = [];

        $this->table->expects(static::once())
                    ->method('fetchOne')
                    ->with(1)
                    ->willReturn($event);

        $data = [
            'status'  => 'error',
            'message' => 'Identifier does not exist',
        ];

        $jsonResponse = $this->assertJsonResponse($response, $data, 404);

        static::assertSame($jsonResponse, $this->controller->details($request, $response));
    }


    public function testDetails(): void
    {
        $request  = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $route          = $this->createMock(RouteInterface::class);
        $routeParser    = $this->createMock(RouteParserInterface::class);
        $routingResults = $this->createMock(RoutingResults::class);

        $request->expects(static::exactly(4))
                ->method('getAttribute')
                ->withConsecutive(
                    [RouteContext::ROUTE],
                    [RouteContext::ROUTE_PARSER],
                    [RouteContext::ROUTING_RESULTS],
                    [RouteContext::BASE_PATH]
                )
                ->willReturn(
                    $route,
                    $routeParser,
                    $routingResults,
                    null,
                    null
                );

        $route->expects(static::once())
              ->method('getArgument')
              ->with('id')
              ->willReturn('1');

        $event = [
            'event_id'   => 1,
            'event_name' => 'Event 1',
        ];

        $this->table->expects(static::once())
                    ->method('fetchOne')
                    ->with(1)
                    ->willReturn($event);

        $data = [
            'status' => 'OK',
            'data'   => $event,
        ];

        $jsonResponse = $this->assertJsonResponse($response, $data);

        static::assertSame($jsonResponse, $this->controller->details($request, $response));
    }

}