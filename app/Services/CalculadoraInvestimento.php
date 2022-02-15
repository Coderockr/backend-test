<?php

namespace App\Services;

use App\Models\Investimento;
use DateTime;

class CalculadoraInvestimento {
    const TAXA_MENSAL = 0.0052;

    const IMPOSTO_INVESTIMENTO_MENOR_DE_UM_ANO = 0.225;
    const IMPOSTO_INVESTIMENTO_DE_UM_HA_DOIS_ANOS = 0.185;
    const IMPOSTO_INVESTIMENTO_MAIOR_DE_DOIS_ANOS = 0.15;
    
    public function obterValoresResgate(Investimento $investimento, $data_resgate = null):array {
        $montante = $this->obterMontanteInvestimento($investimento, $data_resgate);
        $rendimento = $montante - $investimento->valor;
        $valorImposto = $this->obterValorDoImposto($investimento,  $rendimento, $data_resgate);

        
        $valoresResgate = [
            'valor_total' => $montante,
            'valor_imposto' => $valorImposto,
            'valor_real' => $montante - $valorImposto
        ];

        return $valoresResgate;
    }

    public function obterMontanteInvestimento(Investimento $investimento, $data_resgate = null):float {
        $totalDeMeses = $this->obterTotalDeMesesDoInvestimento($investimento->data, $data_resgate);
        $capital = $investimento->valor;
        $taxa = self::TAXA_MENSAL;

        $montante = $capital * pow((1 + $taxa), $totalDeMeses);
        
        return round($montante, 2);
    }

    private function obterValorDoImposto(Investimento $investimento,  float $rendimento, $data_resgate = null) {
        $totalDeMeses = $this->obterTotalDeMesesDoInvestimento($investimento->data, $data_resgate);
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

    private function obterTotalDeMesesDoInvestimento($dataInvestimento, $data_resgate = null):int {
        $dataAtual = new DateTime($data_resgate);
        $dataOrigem = new DateTime($dataInvestimento);
        $interval = $dataAtual->diff($dataOrigem);
        $totalDeMeses = $interval->m + $interval->y * 12;

        return $totalDeMeses;
    }
}