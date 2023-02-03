<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestimentoStoreRequest;


use App\Models\Investidor;
use App\Models\Investimento;
use App\Services\Ganhos;

class InvestimentoController extends Controller
{

    public function create(InvestimentoStoreRequest $request)
    {
        $request->merge([
            'retirou' => false,
            "ganhos" => 0
        ]);
        $investimento = Investimento::create($request->only('data', 'saldo_inicial', 'investidor_id', 'retirou', 'ganhos'));
        return response()->json($investimento, 201);
    }

    public function show($investidor){
        $investidorExiste = Investidor::find($investidor) ?? false;
        if ($investidorExiste != false){
            return Investimento::where('investidor_id', $investidor)->paginate(10);
        } else {
            return response()->json(
                ["success" => false,
                "message" => "investidor invalido"],
                400);
        }
    }

    public function ganhar(){
        $investimentos = Investimento::all()[0];
        $ganhos = new Ganhos($investimentos);

        return $ganhos->saque();
       // return [$investimento, $ganho];
    }
}
