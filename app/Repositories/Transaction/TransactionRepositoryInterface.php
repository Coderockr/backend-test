<?php

namespace App\Repositories\Transaction;

use App\Models\Investment;
use App\Models\Transaction;
use App\Repositories\BaseRepositoryInterface;

interface TransactionRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param Investment $investment
     * @param array $data
     * @return Transaction|false
     */
    public function addTransaction(Investment $investment, array $data): Transaction | false;
}
