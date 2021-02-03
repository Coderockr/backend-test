<?php
declare(strict_types=1);

namespace Api\Tables;

use Api\Models\UserModel;

class UserTable extends AbstractTable
{
    public const DB_TABLE      = 'user';
    public const TABLE_GATEWAY = self::class . 'Gateway';

    protected ?string $modelClass = UserModel::class;

}