<?php

namespace App\Domains\Log\Repositories;

use App\Domains\Log\Models\Log;
use App\Support\Database\Repository\Repository;

class LogRepository extends Repository
{

    protected $modelClass = Log::class;

}