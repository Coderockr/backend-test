<?php
declare(strict_types=1);

namespace Api\Tables;

use Api\Models\AddressModel;

class AddressTable extends AbstractTable
{
    public const DB_TABLE      = 'address';
    public const TABLE_GATEWAY = self::class . 'Gateway';

    protected ?string $modelClass = AddressModel::class;

}