<?php 

namespace App\Service;

use DateTimeImmutable;

class CalcularAliquotaService
{
    public function CalcularAliquota(DateTimeImmutable $dataQueInvestiu):float
    {
        $dataAtual = new \DateTimeImmutable();
        $diferencaDeAno = $dataQueInvestiu->diff($dataAtual)->y;

        if($diferencaDeAno <= 1){
            return 0.225;
        }else if($diferencaDeAno <= 2){
            return 0.185;
        }else{
            return 0.15;
        }
    }
}