<?php

namespace App\Domains\System\Repositories;

use App\Domains\System\Models\Address;
use App\Support\Database\Repository\Repository;

class AddressRepository extends Repository
{
    protected $modelClass = Address::class;
}