<?php
declare(strict_types=1);

namespace Api\Controllers;

use Api\Filters\EventFilter;
use Api\Filters\FilterInterface;
use Api\Tables\AbstractTable;
use Laminas\Paginator\Paginator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractController
{
    protected const PAGE = 'page';

    protected ?AbstractTable $table;

    protected ?FilterInterface $filter;


    public function __construct(?AbstractTable $table, ?FilterInterface $filter)
    {
        $this->table  = $table;
        $this->filter = $filter;
    }


    protected function json(ResponseInterface $response, array $data, int $code = 200): ResponseInterface
    {
        $json = json_encode($data);
        $response->getBody()->write($json);

        return $response->withHeader('Content-Type', 'application/json')
                        ->withStatus($code);
    }


    protected function getPaginationData(Paginator $paginator, ServerRequestInterface $request): array
    {
        $queryParams = $request->getQueryParams();
        $pageNumber  = filter_var($queryParams[self::PAGE] ?? null, FILTER_VALIDATE_INT) ?: null;

        if ($pageNumber === null) {
            return [];
        }

        $paginator->setCurrentPageNumber($pageNumber);

        $pages        = $paginator->getPages();
        $nextPage     = $pages->next ?? null;
        $previousPage = $pages->previous ?? null;

        return [
            'previous_page'      => $previousPage,
            'next_page'          => $nextPage,
            'current_page'       => $pages->current ?? null,
            'items_per_page'     => $pages->itemCountPerPage ?? null,
            'page_count'         => $pages->pageCount ?? null,
            'previous_page_link' => $previousPage ? $this->buildPageUrl($request, $previousPage) : null,
            'next_page_link'     => $nextPage ? $this->buildPageUrl($request, $nextPage) : null,
        ];
    }


    protected function buildPageUrl(ServerRequestInterface $request, int $pageNumber): string
    {
        return $request->getUri()->getPath() . '?' . http_build_query(
                [
                    EventFilter::FILTER_DATE  => $this->filter->getFilterValue(EventFilter::FILTER_DATE, $request),
                    EventFilter::FILTER_STATE => $this->filter->getFilterValue(EventFilter::FILTER_STATE, $request),
                    self::PAGE                => $pageNumber,
                ]
            );
    }

}