<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\Investment as InvestmentContract;
use App\Models\User\Investment;

class InvestmentController extends Controller
{
    public function __construct(InvestmentContract $investment)
    {
        $this->investment = $investment;
    }

    public function store(Request $request): ?Investment
    {

        $this->validate($request, [
            'value' => 'required|numeric|min:1'
        ]);

        return $this->investment->create($request->all());
    }

    public function show(int $id): ?Investment
    {
        return $this->investment->findByID($id);
    }
}
