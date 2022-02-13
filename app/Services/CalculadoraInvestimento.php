<?php

namespace App\Services;

use App\Models\Investimento;
use DateTime;

class CalculadoraInvestimento {
    const TAXA_MENSAL = 0.0052;

    const IMPOSTO_INVESTIMENTO_MENOR_DE_UM_ANO = 0.225;
    const IMPOSTO_INVESTIMENTO_DE_UM_HA_DOIS_ANOS = 0.185;
    const IMPOSTO_INVESTIMENTO_MAIOR_DE_DOIS_ANOS = 0.15;
    
    public function obterValoresResgate(Investimento $investimento):array {
        $montante = $this->obterMontanteInvestimento($investimento);
        $rendimento = $montante - $investimento->valor;
        $valorImposto = $this->obterValorDoImposto($investimento,  $rendimento);

        
        $valoresResgate = [
            'valor_total' => $montante,
            'valor_imposto' => $valorImposto,
            'valor_real' => $montante - $valorImposto
        ];

        return $valoresResgate;
    }

    public function obterMontanteInvestimento(Investimento $investimento):float {
        $totalDeMeses = $this->obterTotalDeMesesDoInvestimento($investimento->data);
        $capital = $investimento->valor;
        $taxa = self::TAXA_MENSAL;

        $montante = $capital * pow((1 + $taxa), $totalDeMeses);
        
        return round($montante, 2);
    }

    private function obterValorDoImposto(Investimento $investimento,  float $rendimento) {
        $totalDeMeses = $this->obterTotalDeMesesDoInvestimento($investimento->data);
        $anos = $totalDeMeses / 12;
       
        if ($anos < 1.0) {
            $taxaImposto = self::IMPOSTO_INVESTIMENTO_MENOR_DE_UM_ANO;
        } else if ($anos >= 1.0 && $anos < 2.0) {
            $taxaImposto = self::IMPOSTO_INVESTIMENTO_DE_UM_HA_DOIS_ANOS;
        } else {
            $taxaImposto = self::IMPOSTO_INVESTIMENTO_MAIOR_DE_DOIS_ANOS;
        }

        return round($rendimento * $taxaImposto, 2);
    }

    private function obterTotalDeMesesDoInvestimento($dataInvestimento):int {
        $dataAtual = new DateTime();
        $dataOrigem = new DateTime($dataInvestimento);
        $interval = $dataAtual->diff($dataOrigem);
        $totalDeMeses = $interval->m + $interval->y * 12;

        return $totalDeMeses;
    }
}