<?php
declare(strict_types=1);

namespace Api\Controllers;

use Laminas\Paginator\Paginator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;

final class EventController extends AbstractController
{
    public function list(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->filter->addFilters($request);

        $result = $this->table->fetchAll($this->filter);

        $data = [
            'status'     => 'OK',
            'pagination' => $result instanceof Paginator ? $this->getPaginationData($result, $request) : [],
        ];

        $events = [];
        foreach ($result as $event) {
            $events[] = $event;
        }

        $data['data'] = $events;

        return $this->json($response, $data);
    }


    public function details(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = (int) RouteContext::fromRequest($request)->getRoute()->getArgument('id');

        $event = $this->table->fetchOne($id);

        if (empty($event)) {
            return $this->json(
                $response,
                [
                    'status'  => 'error',
                    'message' => 'Identifier does not exist',
                ],
                404
            );
        }

        return $this->json(
            $response,
            [
                'status' => 'OK',
                'data'   => $event,
            ]
        );
    }

}