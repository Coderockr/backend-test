<?php

namespace App\Services;

use App\Http\Requests\InvestimentoStoreRequest;
use App\Models\Investidor;
use App\Models\Investimento;


class Ganhos
{
    private $investimento;
    private $taxas;
    public function __construct(Investimento $investimento)
    {
        $this->investimento = $investimento;
        $this->taxas = [
            "menosDeUmAno" => 0.225,
            "entreDoisEumAno" => 0.185,
            "maisDeDoisAno" => 0.15
        ];
    }

    public function calcularGanhos()
    {
        $saldoAtual =  $this->investimento->saldo_inicial;
        $saltoTotal = $saldoAtual + $this->investimento->ganhos;
        $ganho = $saltoTotal * 0.0052;
        $novoGanho  = $this->investimento->ganhos + $ganho;
        return [
            "ganhos" => round($novoGanho, 1)
        ];
    }

    public function TaxaSobreGanhos () {
        $saldoAtual = $this->investimento->saldo_inicial;
        $ganhos =  $this->investimento->ganhos;
        $dataInvestimento = splitData($this->investimento->data);
        $dataAtual = new \DateTime();
        $anoAtual =$dataAtual->format('Y');
        $diferencaAno =  $anoAtual - $dataInvestimento['ano'];
        $ganhoFinal = 0;
        if ($diferencaAno <= 0){
            $ganhoFinal = $ganhos - $ganhos * $this->taxas['menosDeUmAno'];
        }elseif ($diferencaAno == 1 or $diferencaAno == 2){
            if ($diferencaAno == 1){
                $taxa =  $this->pegarTaxa($dataInvestimento, $dataAtual);
                $ganhoFinal = $ganhos - $ganhos * $taxa;
            }else {
                $ganhoFinal = $ganhos - $ganhos * $this->taxas['entreDoisEumAno'];
            }
        } elseif ($diferencaAno > 2){
            $ganhoFinal = $ganhos - $ganhos * $this->taxas['maisDeDoisAno'];
        }
        return $ganhoFinal;
    }

    private function pegarTaxa($dataInvestimento, $dataAtual)
    {
        $mesDoInvestimento = $dataInvestimento['mes'];
        $mesAtual = $dataAtual->format('m');
        $diferencaMes = $mesAtual - $mesDoInvestimento;
        if ($diferencaMes == 0){
            $diaInvestimento = $dataInvestimento['dia'];
            $diaAtual = $dataAtual->format('d');
            $diferencaDia = $diaAtual - $diaInvestimento;
            if ($diferencaDia <= 0){
                return $this->taxas['entreDoisEumAno'];
            }else {
                return $this->taxas['menosDeUmAno'];
            }
        }elseif ($diferencaMes > 0){
            return $this->taxas['menosDeUmAno'];
        }else {
            return $this->taxas['entreDoisEumAno'];
        }
    }
}
