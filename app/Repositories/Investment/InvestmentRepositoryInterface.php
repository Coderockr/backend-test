<?php

namespace App\Repositories\Investment;

use App\Models\Investment;
use App\Repositories\BaseRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface InvestmentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param Carbon $date
     * @return Collection
     */
    public function allByDate(Carbon $date): Collection;

    /**
     * @param $investor_id
     * @param $id
     * @return Investment
     */
    public function getInvestmentFromInvestor($investor_id, $id): Investment;
}
