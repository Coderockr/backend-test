<?php

namespace App\Http\Controllers;

use App\Http\Requests\Investment\InvestmentCreateRequest;
use App\Models\Owner;
use App\Services\InvestmentService;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    public function __construct(private InvestmentService $service)
    {
    }

    public function create(InvestmentCreateRequest $request)
    {
        $data = $request->validated();
        $data['owner_id'] = Owner::where('email', $data['email'])->first()->id;
        return $this->service->create($data);
    }
}
