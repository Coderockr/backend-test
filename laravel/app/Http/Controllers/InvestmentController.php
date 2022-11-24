<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestmentRequest;
use App\Models\Investment;
use App\Models\Owner;
use App\Repositories\InvestmentRepository;
use App\Repositories\OwnerRepository;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvestmentRequest $request)
    {
        try {
            $ownerRepo = new OwnerRepository;
            $ownerId = $ownerRepo->setOwner($request->owner_id, $request->owner_name);
            
            $investmentRepo = new InvestmentRepository;
            $investmentRepo->setInvestment($request->invesment, $ownerId);
            
            return $this->success($request->input(), "Investmento criado com sucesso");
        } catch (\Exception $e) {
            return $this->error("Houve um erro interno ao criar um investimento.", 500, ["detail" => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $ownerId
     * @return \Illuminate\Http\Response
     */
    public function show(int $ownerId)
    {
        try {
            $investmentRepo = new InvestmentRepository;
            $investment = $investmentRepo->getInvestmentByOwner($ownerId);
            $investmentGain = $investmentRepo->getValueWithGain($investment);
            
            return $this->success($investmentGain, "Investmento consultado com sucesso");
        } catch (\Exception $e) {
            return $this->error("Houve um erro interno ao buscar um investimento.", 500, ["detail" => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Investment  $investment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Investment $investment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Investment  $investment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Investment $investment)
    {
        //
    }
}
