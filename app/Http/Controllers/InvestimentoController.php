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
        $validator = Validator::make($request->all(), [
            'id_investimento' => 'required|numeric',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $id_investimento = $request->get('id_investimento');
        $investimento = Investimento::find($id_investimento);
        if (!$investimento) {
            return response()->json(['erro' => 'investimento não localizado'], 404);
        }

        $calculadora = new CalculadoraInvestimento();
        $montanteInvestimento = $calculadora->obterMontanteInvestimento($investimento);

        $informacaoInvestimento = [
            'valor_inicial' => $investimento->valor,
            'saldo_esperado' => $montanteInvestimento,
            'cpf_investidor' => $investimento->cpf_investidor,
            'data_investimento' => $investimento->data
        ];

        return response()->json($informacaoInvestimento, 200);
    }

    public function resgatar(Request $request) {
        $validator = Validator::make($request->all(), [
            'id_investimento' => 'required|numeric',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $id_investimento = $request->get('id_investimento');
        $investimento = Investimento::find($id_investimento);
        if (!$investimento) {
            return response()->json(['erro' => 'investimento não localizado'], 404);
        }

        $calculadora = new CalculadoraInvestimento();
        $valoresResgate = $calculadora->obterValoresResgate($investimento);
        $valoresResgate['cpf_investidor'] = $investimento->cpf_investidor;
        $valoresResgate['data_investimento'] = $investimento->data;

        $investimento->sacado = true;
        
        return response()->json($valoresResgate, 200);
    }
}
