<?php

namespace App\Http\Controllers;

use App\Models\Investimento;
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
}
