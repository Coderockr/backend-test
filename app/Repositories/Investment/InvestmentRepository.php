<?php

namespace App\Repositories\Investment;

use App\Models\Investment;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class InvestmentRepository extends BaseRepository implements InvestmentRepositoryInterface
{
    /**
     * @var Investment
     */
    protected Investment $model;

    /**
     * @param Investment $investment
     */
    public function __construct(Investment $investment)
    {
        $this->model = $investment;
    }

    /**
     * @param Carbon $date
     * @return Collection
     */
    public function allByDate(Carbon $date): Collection
    {
        return $this->model->whereDate('last_applied_rate', $date)->get();
    }

    /**
     * @param string $investor_id
     * @return Collection
     */
    public function getInvestmentsFromInvestor(string $investor_id): Collection
    {
        return $this->model->where('investor_id', $investor_id)->get();
    }

    /**
     * @param $investor_id
     * @param $id
     * @return Investment
     */
    public function getInvestmentFromInvestor($investor_id, $id): Investment
    {
        return $this->model->where('investor_id', $investor_id)->findOrFail($id);
    }
}
