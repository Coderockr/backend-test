<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\Investment as InvestmentContract;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as PaginateList;
use App\Models\User\Investment;
use Auth;

class InvestmentRepository implements InvestmentContract
{
    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function create(array $data): Investment
    {
        return $this->user->investments()->create($data);
    }

    public function findByID(int $id): Investment
    {
        return $this->user->investments()->where('id', $id)->firstOrFail();
    }

    public function withdraw(Investment $investment, ?string $dateTime = null): bool
    {
        return $investment->setAsWithdrawn($dateTime);
    }

    public function index(?Request $request): PaginateList
    {   
        $investments = $this->user->investments();

        $perPage = $request->get('perPage');
        $perPage = $perPage ? intval($perPage) : 15;

        $orderBy = $request->get('order') ?? null;

        if($orderBy) {
            $order = explode(',', $this->indexOrderBy($orderBy));
            $investments = $investments->orderBy($order[0], $order[1]);
        } else {
            $investments = $investments->orderBy('created_at', 'desc');
        }

        $where = $request->get('filter');

        if($where == 'withdrawn')
            $investments = $investments->where('withdrawn', true);

        if($where == 'open')
            $investments = $investments->where('withdrawn', false);

        return $investments
            ->paginate($perPage)
            ->appends($request->query());
    }

    private function indexOrderBy(string $order): string
    {
        $orderBy = null;

        switch ($order) {
            case 'most_recent':
                $orderBy = 'created_at,desc';
                break;

            case 'most_old':
                $orderBy = 'created_at,asc';
                break;

            case 'highest_value':
                $orderBy = 'value,desc';
                break;

            case 'lowest_value':
                $orderBy = 'value,asc';
                break;

            case 'withdrawn':
                $orderBy = 'withdrawn,desc';
                break;
            
            case 'open':
                $orderBy = 'withdrawn,asc';
                break;

            default:
                $orderBy = 'created_at,desc';
                break;
        }

        return $orderBy;
    }
}