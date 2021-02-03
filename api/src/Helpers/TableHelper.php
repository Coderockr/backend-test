<?php
declare(strict_types=1);

namespace Api\Helpers;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;

// @codeCoverageIgnoreStart
class TableHelper
{
    public function newSelect(?string $table = null): Select
    {
        return new Select($table);
    }


    public function newDbSelect(Select $select, Adapter $adapter): DbSelect
    {
        return new DbSelect($select, $adapter);
    }


    public function newPaginator(DbSelect $dbSelect): Paginator
    {
        return new Paginator($dbSelect);
    }

}