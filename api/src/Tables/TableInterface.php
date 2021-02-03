<?php
declare(strict_types=1);

namespace Api\Tables;

use Api\Filters\FilterInterface;
use Api\Models\ModelInterface;
use Laminas\Db\Adapter\Driver\ResultInterface;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Paginator\Paginator;

interface TableInterface
{
    public function fetchAll(
        array|FilterInterface $filter,
        bool $isPaginated = true
    ): ResultInterface|ResultSetInterface|Paginator;


    public function fetchOne(int|ModelInterface $idOrModel): ModelInterface|array;


    public function saveOne(ModelInterface $model): bool;


    public function deleteOne(int|ModelInterface $idOrModel): bool;

}