<?php

namespace App\Traits;

use App\Models\Investimento;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

trait InvestimentoTrait
{
    public function calculaGanhoEsperado(Investimento $investimento): array
    {
        $mesesIntervalo = CarbonPeriod::create(Carbon::parse($investimento->data_criacao)->toDateString(), now()->toDateString())->month();

        $ganho = 0;
        $investimento_valor = 0;
        $ganhos = [];
        foreach ($mesesIntervalo as $key => $meses) {

            if ($key === 0) {
                $ganho = $investimento->valor_inicial * 0.0052; // 1 - Mês
                $investimento_valor = $investimento->valor_inicial; // 1 - Mês
            } else {
                list($investimento_valor, $ganho) = $this->retornaGanho($ganho, $investimento_valor);
            }

            array_push($ganhos, [
                'periodo' => $meses->format("d-m-Y"),
                'saldo_investimento' => (float) bcadd($investimento_valor, 0, 2)
            ]);
        }

        return [
            'tipo_investimento' => $investimento->investimento,
            'investimento_inicial' => $investimento->valor_inicial,
            'saldo_esperado' => end($ganhos)['saldo_investimento'],
            'ganhos_mensais' => $ganhos
        ];
    }

    private function retornaGanho($ganho, $investimento_valor)
    {
        return [($investimento_valor + $ganho), (($investimento_valor + $ganho) * 0.0052)];
    }

    public function calculoImposto(string $dataInicialInvest, string $dataRetiradaInvest, float $ganhoInvest)
    {

        $dias = Carbon::parse($dataInicialInvest)->diffInDays($dataRetiradaInvest);

        return match (true) {
            $dias < 366 => ['valor_imposto' => (float) bcadd(($ganhoInvest * 0.225), 0, 2), 'percentual' => (0.225 * 100)],
            in_array($dias, range(366, 730)) => ['ganho_invest' => (float) bcadd(($ganhoInvest * 0.185), 0, 2), 'percentual' => (0.185 * 100)],
            $dias > 730 => ['valor_imposto' => (float) bcadd(($ganhoInvest * 0.15), 0, 2), 'percentual' => (0.15 * 100)],
            default => ['valor_imposto' => (float) bcadd($ganhoInvest, 0, 2), 'percentual' => 0]
        };
    }

    public function formataMoedaParaBrl(float $valor)
    {
        $formataMoeda = new \NumberFormatter("pt-BR", \NumberFormatter::CURRENCY);
        return preg_replace('/[R$]/', '', $formataMoeda->formatCurrency($valor, "BRL"));
    }

    /**
     * Verificar se a data de retirada do investimento é menor que a data de criação
     * Verificar se a data de retirada do investimento é maior que a data atual
     *
     * @param string $dataInicialInves
     * @param string $dataRetiradaInves
     *
     * @return bool
     */
    public function validarDataRetiradaInvestimento(string $dataInicialInvest, string $dataRetiradaInvest): bool|string
    {
        if (Carbon::parse($dataRetiradaInvest)->lte($dataInicialInvest) || Carbon::parse($dataRetiradaInvest)->gte(now())) {
            return true;
        }

        return false;
    }
}
