<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\Investment as InvestmentContract;
use App\Models\User\Investment;
use Auth;

class InvestmentRepository implements InvestmentContract
{
    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function create(array $data): Investment
    {
        return $this->user->investments()->create($data);
    }

    public function findByID(int $id): Investment
    {
        return $this->user->investments()->where('id', $id)->firstOrFail();
    }

    public function withdraw(Investment $investment, ?string $dateTime = null): bool
    {
        return $investment->setAsWithdrawn($dateTime);
    }
}