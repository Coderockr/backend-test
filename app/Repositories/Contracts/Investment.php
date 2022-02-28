<?php

namespace App\Repositories\Contracts;

interface Investment
{
    public function create(array $data);

    public function findByID(int $id);
}