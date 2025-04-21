<?php

namespace App\Service;

use App\Service\PegarDataAtualService;
use DateTimeImmutable;

class CalcularGanhoService
{

    public function __construct(
        private PegarDataAtualService $pegarDataAtualService
        
    )
    {
    }

    public function calcularGanho(
        DateTimeImmutable $dataQueInvestiu,
        float $valorIniciaInvestimento,
        float $valorAliquota
        
    ): array
    {
        $diferencaDoMes= $dataQueInvestiu->diff($this->pegarDataAtualService->obterDataAtual());
        $messesDoInvestimento = ($diferencaDoMes->y * 12) + $diferencaDoMes->m;
         
        $valorInicialDoInvestimento = $valorIniciaInvestimento;
        $valorQueRendeuSobreInvestimento = $valorInicialDoInvestimento * (1 + 0.0052) ** $messesDoInvestimento;
        $ganhoSobreInvestimento = $valorQueRendeuSobreInvestimento - $valorInicialDoInvestimento;

        $impostoSobreInvestimento = $valorAliquota * $ganhoSobreInvestimento;

        return [
            "impostoSobreInvestimento" =>  $impostoSobreInvestimento,
            "valorQueRendeuSobreInvestimento" =>  $valorQueRendeuSobreInvestimento
        ];
    }
}

