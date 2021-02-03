<?php
declare(strict_types=1);

namespace Api\Filters;

use Laminas\Db\Sql\Where;
use Psr\Http\Message\ServerRequestInterface;

interface FilterInterface
{
    public function addFilters(ServerRequestInterface $request): self;


    public function getFilterForDb(?ServerRequestInterface $request = null): array|Where;

}