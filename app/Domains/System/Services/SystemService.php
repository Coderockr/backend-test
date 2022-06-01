<?php

namespace App\Domains\System\Services;

use App\Domains\System\Repositories\JobRepository;

class SystemService
{

    public function getQueue(array $data)
    {
        $repo = new JobRepository();
        return $repo->newQuery()->where('queue', $data['queue'])->get();
    }
    
}
