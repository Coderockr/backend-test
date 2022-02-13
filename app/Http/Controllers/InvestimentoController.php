<?php

namespace App\Http\Controllers;

use App\Models\Investimento;
use App\Services\CalculadoraInvestimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvestimentoController extends Controller
{
    public function store(Request $request) {
        $requestData = $request->only(['valor', 'cpf_investidor', 'data']);

        $validator = Validator::make($requestData, [
            'valor' => 'required|numeric|min:0.01',
            'cpf_investidor' => 'required|string',
            'data' => 'required|date|before_or_equal:' . date('Y-m-d'),
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $investimento = new Investimento($requestData);
        $investimento->save();

        return response()->json($investimento, 201);
    }

    public function show(Request $request) {
        $id_investimento = $request->route('id_investimento');
        
        $investimento = Investimento::find($id_investimento);
        if (!$investimento) {
            return response()->json(['erro' => 'investimento não localizado'], 404);
        }

        $calculadora = new CalculadoraInvestimento();
        $montanteInvestimento = $calculadora->obterMontanteInvestimento($investimento);

        $informacaoInvestimento = [
            'valor_inicial' => $investimento->valor,
            'saldo_esperado' => $montanteInvestimento,
            'cpf_investidor' => $investimento->cpf_investidor
        ];

        return response()->json($informacaoInvestimento, 200);
    }
}
