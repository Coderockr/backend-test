<?php

namespace App\Domains\System\Repositories;

use App\Domains\System\Models\Job;
use App\Support\Database\Repository\Repository;

class JobRepository extends Repository
{

    protected $modelClass = Job::class;

}