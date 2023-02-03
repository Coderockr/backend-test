<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaqueRequest;
use App\Models\Investimento;
use App\Services\Ganhos;
use Illuminate\Http\Request;

class SaqueController extends Controller
{
    public function sacar(SaqueRequest $request)
    {
        $investimento = Investimento::find($request->investimento);
        $service = new Ganhos($investimento);
        $taxa = $service->TaxaSobreGanhos();
        $investimento->update([
           "retirou" => true
        ]);
        $valorRetirado = $investimento->saldo_inicial + $investimento->ganhos -$taxa;
        return response()->json([
            "message" => "saque realizado !",
            "success" => true,
            "valorBruto" => $investimento->saldo_inicial + $investimento->ganhos,
            "taxa" => $taxa,
            "valorLiquido" => $valorRetirado
            ]);
    }
}
