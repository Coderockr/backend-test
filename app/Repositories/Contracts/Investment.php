<?php

namespace App\Repositories\Contracts;

use App\Models\User\Investment as InvestmentModel;

interface Investment
{
    public function create(array $data);

    public function findByID(int $id);

    public function withdraw(InvestmentModel $investment, ?string $dateTime);
}