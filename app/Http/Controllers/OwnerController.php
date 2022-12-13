<?php

namespace App\Http\Controllers;

use App\Http\Requests\Owner\OwnerCreateRequest;
use App\Http\Requests\Owner\OwnerInvestmentsRequest;
use App\Services\OwnerService;

class OwnerController extends Controller
{
    public function __construct(private OwnerService $service)
    {
    }

    public function create(OwnerCreateRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function showInvestments(OwnerInvestmentsRequest $request)
    {
        return $this->service->showInvestments($request->validated());
    }
}
