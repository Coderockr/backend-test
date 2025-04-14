<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestmentRequest;
use App\Models\Investment;
use App\Models\Owner;
use App\Repositories\InvestmentRepository;
use App\Repositories\OwnerRepository;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class InvestmentController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $investmentRepo = new InvestmentRepository;
            $allInvestments = $investmentRepo->getInvestmentByOwner($request->owner_id);
            
            return $this->success($allInvestments, "Investmento criado com sucesso");
        } catch (\Exception $e) {
            return $this->error("Houve um erro interno ao buscar a lista de todos os investimentos.", 500, ["detail" => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
    public function show(int $investmentId)
    {
        try {
            $validator = Validator::make(["investmentId" => $investmentId], [
                'investmentId' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return $this->error(
                    'Falha ao realizar ao consultar um investmento.',
                    Response::HTTP_BAD_REQUEST,
                    $validator->errors()
                );
            }
            $investmentRepo = new InvestmentRepository;
            $investment = $investmentRepo->getInvestmentById($investmentId);
            $investmentGain = $investmentRepo->getValueWithGain($investment);
            
            return $this->success($investmentGain, "Investmento consultado com sucesso");
        } catch (\Exception $e) {
            return $this->error("Houve um erro interno ao buscar um investimento.", Response::HTTP_INTERNAL_SERVER_ERROR, ["detail" => $e->getMessage()]);
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
        try {
            $validator = Validator::make(
                [
                    "investmentId" => $investment->id,
                    "date" => $request->date
                ], 
                [
                    'investmentId' => 'required|numeric|exists:investments,id',
                    'date' => 'required'
                ]);
            if ($validator->fails()) {
                return $this->error(
                    'Falha ao realizar a retirada de um investmento.',
                    Response::HTTP_BAD_REQUEST,
                    $validator->errors()
                );
            }
            $investmentRepo = new InvestmentRepository;
            $investment = $investmentRepo->withdrawalInvestment($investment, $request->date);
            
            return $this->success($investment, "Investmento retirado com sucesso");
        } catch (\Exception $e) {
            return $this->error("Houve um erro interno ao retirar um investimento.", Response::HTTP_INTERNAL_SERVER_ERROR, ["detail" => $e->getMessage()]);
        }
    
    }
}
