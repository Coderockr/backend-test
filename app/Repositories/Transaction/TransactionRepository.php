<?php

namespace App\Repositories\Transaction;

use App\Models\Investment;
use App\Models\Transaction;
use App\Repositories\BaseRepository;

class TransactionRepository extends BaseRepository implements TransactionRepositoryInterface
{
    /**
     * @var Transaction
     */
    protected Transaction $model;

    /**
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->model = $transaction;
    }

    /**
     * @param Investment $investment
     * @param array $data
     * @return Transaction|false
     */
    public function addTransaction(Investment $investment, array $data): Transaction | false
    {
        $transaction = $this->model::make($data);
        return $investment->transactions()->save($transaction);
    }
}
