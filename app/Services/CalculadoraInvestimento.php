<?php

namespace App\Services;

use App\Models\Investimento;
use DateTime;

class CalculadoraInvestimento {
    const TAXA_MENSAL = 0.0052;

    public function obterMontanteInvestimento(Investimento $investimento):float {
        //obtém total de meses do investimento até hoje
        $dataAtual = new DateTime();
        $dataOrigem = new DateTime($investimento->data);
        $interval = $dataAtual->diff($dataOrigem);
        $totalDeMeses = $interval->m + $interval->y * 12;
        
        $capital = $investimento->valor;
        $taxa = self::TAXA_MENSAL;

        $montante = $capital * pow((1 + $taxa), $totalDeMeses);
        
        return round($montante, 2);
    }
}