<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as PaginateList;
use App\Repositories\Contracts\Investment as InvestmentContract;
use App\Models\User\Investment;

class InvestmentController extends Controller
{
    public function __construct(InvestmentContract $investment)
    {
        $this->investment = $investment;
    }

    public function index(Request $request): PaginateList
    {
        return $this->investment->index($request);
    }

    public function store(Request $request): ?Investment
    {
        $this->validate($request, [
            'value' => 'required|numeric|min:1',
            'created_at' => 'date|date_format:Y-m-d H:i:s|before_or_equal:now'
        ]);

        return $this->investment->create($request->all());
    }

    public function show(int $id): ?Investment
    {
        return $this->investment->findByID($id);
    }

    public function withdraw(Request $request, int $id): bool
    {
        $investment = $this->investment->findByID($id);

        $this->validate($request, [
            'date' => 'date|date_format:Y-m-d H:i:s|before_or_equal:now|after:'.$investment->created_at
        ]);

        return $this->investment->withdraw( $investment, $request->get('date') );
    }
}
