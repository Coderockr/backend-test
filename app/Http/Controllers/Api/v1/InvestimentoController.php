<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvestimentoRequest;
use App\Http\Requests\InvestimentoRetiradaRequest;
use App\Http\Resources\InvestimentoCollection;
use App\Http\Resources\InvestimentoResource;
use App\Http\Resources\InvestimentoRetiradaResource;
use App\Models\Investimento;
use App\Models\User;
use App\Services\InvestimentoService;

class InvestimentoController extends Controller
{

    public function __construct(private InvestimentoService $investimentoService)
    {
        $this->investimentoService = $investimentoService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvestimentoRequest $request)
    {
        return new InvestimentoResource($this->investimentoService->criarInvestimento($request));
    }

    /**
     * Display the specified investiment resource.
     */
    public function showInvestimentoInvestidor(string $investimento, User $investidor)
    {
        return response()->json([
            'status' => 'Success',
            'statuscode' => 200,
            'data' => $this->investimentoService->visualizarInvestimento($investimento, $investidor),
            'message' => 'Saldo de Investimento e Ganhos recuperados com sucesso!',
        ]);
    }

    /**
     * Display the specified investiment user.
     */
    public function showInvestimentosInvestidor(User $investidor)
    {
        return new InvestimentoCollection($this->investimentoService->listaInvestimentosInvestidor($investidor));
    }

    /**
     * Display the simulate removal investiment user.
     */
    public function simularRetiradaInvestimentoInvestidor(string $investimento, string $data_retirada, int $investidor)
    {
        return response()->json([
            'status' => 'Success',
            'statuscode' => 200,
            'data' => new InvestimentoRetiradaResource($this->investimentoService->simularRetirarInvestimento(
                investimento: $investimento,
                data_retirada: $data_retirada,
                investidor: $investidor
            )),
            'message' => 'Simulação do saque de Investimento recuperada com sucesso!',
        ]);
    }

    /**
     * Display the removal investiment user.
     */
    public function retiradaInvestimentoInvestidor(InvestimentoRetiradaRequest $request)
    {
        return new InvestimentoRetiradaResource($this->investimentoService->retirarInvestimento(
            investimento: $request->investimento,
            data_retirada: $request->data_retirada,
            investidor: $request->investidor
        ));
    }
}
