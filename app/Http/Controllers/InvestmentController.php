<?php

namespace App\Http\Controllers;

use App\Http\Requests\Investment\{InvestmentCreateRequest, InvestmentShowRequest, WithdrawalRequest};
use App\Models\Owner;
use App\Services\InvestmentService;

class InvestmentController extends Controller
{
    public function __construct(private InvestmentService $service)
    {
    }

    public function show(InvestmentShowRequest $request)
    {
        return $this->service->show($request->validated());
    }

    public function create(InvestmentCreateRequest $request)
    {
        $data = $request->validated();
        $data['owner_id'] = Owner::where('email', $data['email'])->first()->id;
        return $this->service->create($data);
    }

    public function withdrawal(WithdrawalRequest $request)
    {
        return $this->service->withdrawal($request->validated());
    }
}
