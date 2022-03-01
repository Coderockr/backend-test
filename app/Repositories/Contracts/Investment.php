<?php

namespace App\Repositories\Contracts;

use App\Models\User\Investment as InvestmentModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as PaginateList;
use Illuminate\Http\Request;

interface Investment
{
    public function index(?Request $request): PaginateList;

    public function create(array $data): InvestmentModel;

    public function findByID(int $id): InvestmentModel;

    public function withdraw(InvestmentModel $investment, ?string $dateTime): bool;

}