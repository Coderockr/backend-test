<?php

namespace App\Domains\Log\Services;

use App\Domains\Log\Repositories\LogRepository;

class LogService
{
    /**
     * @var LogRepository
     */
    private $repo;

    public function __construct(LogRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * Retorna todos os logs.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getItems()
    {
        return $this->repo->getAll();
    }

    /**
     * @param array $data
     */
    public function create(array $data)
    {
        return $this->repo->create($data);
    }
    
}