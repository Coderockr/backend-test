<?php

namespace App\Http\Controllers;

use App\Models\Investimento;
use App\Services\CalculadoraInvestimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DateTime;

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
        $requestData = $request->only(['id_investimento', 'data_resgate']);

        $validator = Validator::make($requestData, [
            'id_investimento' => 'required|numeric',
            'data_resgate' => 'required|date|before_or_equal:' . date('Y-m-d'),
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $investimento = Investimento::find($requestData['id_investimento']);

        //validações
        if (!$investimento) {
            return response()->json(['erro' => 'investimento não localizado'], 404);
        }

        if ($investimento->resgatado === true) {
            return response()->json(['erro' => 'Saldo já sacado'], 400);
        }
        
        $dataInvestimento = new DateTime($investimento->data);
        $dataResgate = new DateTime($requestData['data_resgate']);
        if ($dataResgate < $dataInvestimento) {
            return response()->json(['erro' => 'A data doi resgate não pode ser inferior a data do investimento.'], 400);
        }

        $calculadora = new CalculadoraInvestimento();
        $valoresResgate = $calculadora->obterValoresResgate($investimento, $requestData['data_resgate']);
        $valoresResgate['cpf_investidor'] = $investimento->cpf_investidor;
        $valoresResgate['data_investimento'] = $investimento->data;

        $investimento->resgatado = true;
        $investimento->save();
        
        return response()->json($valoresResgate, 200);
    }
}
