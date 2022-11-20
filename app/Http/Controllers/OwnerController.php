<?php

namespace App\Http\Controllers;

use App\Http\Requests\Owner\OwnerCreateRequest;
use App\Services\OwnerService;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function __construct(private OwnerService $service)
    {
    }

    public function create(OwnerCreateRequest $request)
    {
        return $this->service->create($request->validated());
    }
}
