<?php

namespace App\Services;

use App\Exceptions\InvestAPIException;
use App\Models\Investimento;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\InvestimentoTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

class InvestimentoService
{
    use InvestimentoTrait;

    public function __construct(private Investimento $investimento)
    {
        $this->investimento = $investimento;
    }

    public function listaInvestimentosInvestidor(User $investidor)
    {
        return $this->investimento->where('investidor_id', $investidor->id)->paginate();
    }

    public function criarInvestimento(Request $request)
    {
        $investimento = $this->investimento->create([
            'investimento' => $request->investimento,
            'investidor_id' => $request->investidor_id,
            'valor_inicial' => $request->valor_inicial,
            'data_criacao' => $request->data_criacao
        ]);

        return $investimento;
    }

    public function visualizarInvestimento(string $investimento, User $investidor)
    {

        try {
            DB::beginTransaction();

            $investimento = $this->investimento->where([['investimento', $investimento], ['investidor_id', $investidor->id]]);
            $saldo_esperado = $this->calculaGanhoEsperado($investimento->first());

            $saldo_investimento_final = $investimento->update([
                'valor_final' => $saldo_esperado['saldo_esperado'],
            ]);

            if ($saldo_investimento_final) {
                DB::commit();
                return $saldo_esperado;
            }
        } catch (Throwable) {
            DB::rollBack();
            return response()->json(
                [
                    'status' => 'Error',
                    'statuscode' => 500,
                    'message' => 'Ouve um erro ao Salvar o Saldo do Investimento!!!',
                ],
                500
            );
        }
    }

    public function simularRetirarInvestimento(string $investimento, string $data_retirada, int $investidor)
    {
        $investimento_investidor = $this->investimento->where([['investimento', $investimento], ['investidor_id', $investidor]])
            ->whereNull('data_retirada')->first();

        if (!is_null($investimento_investidor)) {
            $investimento_ganho = $this->calculaGanhoEsperado($investimento_investidor);

            if (!$this->validarDataRetiradaInvestimento(dataInicialInvest: $investimento_investidor->data_criacao, dataRetiradaInvest: $data_retirada)) {

                $imposto = $this->calculoImposto(
                    dataInicialInvest: $investimento_investidor->data_criacao,
                    dataRetiradaInvest: $data_retirada,
                    ganhoInvest: ($investimento_ganho['saldo_esperado'] - $investimento_investidor->valor_inicial)
                );

                return [
                    'data_retirada' => $data_retirada,
                    'valor_saque' => (float) bcadd(($investimento_investidor->valor_final - $imposto['valor_imposto']), 0, 2),
                    'imposto' => $imposto,
                ];
            }

            throw new InvestAPIException(message: 'Data não permitida para retirada de Investimento! Tente outra data!', code: 422);
        }

        throw new InvestAPIException(message: 'Este investimento já foi retirado!', code: 422);
    }

    public function retirarInvestimento(string $investimento, string $data_retirada, int $investidor)
    {
        $dados_retirada_investimento = $this->simularRetirarInvestimento($investimento, $data_retirada, $investidor);

        try {
            DB::beginTransaction();

            $salvar_retirada = $this->investimento->where([['investimento', $investimento], ['investidor_id', $investidor]])
                ->whereNull('data_retirada')
                ->update([
                    'data_retirada' => $dados_retirada_investimento['data_retirada'],
                    'valor_retirada' => $dados_retirada_investimento['valor_saque'],
                    'valor_imposto' => $dados_retirada_investimento['imposto']['valor_imposto'],
                    'taxa_imposto' => $dados_retirada_investimento['imposto']['percentual']
                ]);

            if ($salvar_retirada) {
                DB::commit();
                return $dados_retirada_investimento;
            }
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(
                [
                    'status' => 'Error',
                    'statuscode' => 500,
                    'message' => 'Ouve um erro ao realizar a retirada do investimento!!!' . $e->getMessage(),
                ],
                500
            );
        }
    }
}
