<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvestiment;
use App\Http\Resources\InvestimentResource;
use App\Repositories\InvestimentRepository;
use Illuminate\Http\Request;

class InvestimentController extends Controller
{
    protected $repository;

    public function __construct(InvestimentRepository $investimentRepository)
    {
        $this->repository = $investimentRepository;
    }
    
    public function index()
    {
        return InvestimentResource::collection($this->repository->getAllInvestiments());
    }

    public function store(StoreInvestiment $request)
    {
         return $this->repository->createNewInvestment($request->validated());
    }

    public function show($id)
    {
        $investiment = new InvestimentResource($this->repository->getInvestiment($id));
        
        $currentValue = $this->repository->getCurrentValue($investiment->value, $investiment->investiment_date);
        $investiment['current_value'] = $currentValue;
        $investiment['income'] =  (float) number_format($currentValue - $investiment->value, 2, '.', '');

        return $investiment;
    }

}
