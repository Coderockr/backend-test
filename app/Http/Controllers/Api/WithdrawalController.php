<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWithdrawal;
use App\Repositories\WithdrawalRepository;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    protected $repository;

    public function __construct(WithdrawalRepository $withdrawalRepository)
    {
        $this->repository = $withdrawalRepository;
    }

    public function store(StoreWithdrawal $request)
    {
        return $this->repository->saveWithdrawal($request->validated());
    }
}
