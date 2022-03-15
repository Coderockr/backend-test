<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Withdrawal extends BaseController
{
    protected $helpers = ['apoio'];
    protected $modelInvestiment;
    protected $modelProducts;
    protected $modelUser;
    protected $modelWithdrawal;
    use ResponseTrait;

    public function __construct()
    {
        $this->modelInvestiment = new \App\Models\Investments();
        $this->modelProducts = new \App\Models\Products();
        $this->modelUser = new \App\Models\Users();
        $this->modelWithdrawal = new \App\Models\Withdrawal();
    }
    public function index()
    {
        return $this
            ->respond([
            'body' => $this->modelInvestiment->where('deleted_at',null)->get()->getResult()
        ],200);
    }

    /**
     * @return \CodeIgniter\HTTP\Response
     * @throws \ReflectionException
     */
    public function create()
    {
        $data = $this->request->getVar();
        $investimento = $this->modelInvestiment->asObject()->find($data['investiment_id']) ;

        if (!$investimento):
            return $this->failNotFound("Não foi encontrado o investimento especificado.",404);
        endif;

        $validar_dados = $this->validateRequest($data,$investimento);

        if ($validar_dados['success'] == false):
            return $this->failNotFound($validar_dados['body'],404);
        endif;

        $calcular_diferenca_entre_datas = diffDates($investimento->transaction_date,$data['data_withdrawal']);
        $meses = $calcular_diferenca_entre_datas['y'] * 12 + $calcular_diferenca_entre_datas['m'];

        if($meses <= 12 ):
            $tax = 0.225;
        elseif ($meses > 12 && $meses <= 24):
            $tax = 0.185;
        elseif ($meses > 24):
            $tax = 0.15;
        endif;

        $invest_init = floatval($investimento->transaction_ammount);

        $calcular_juros = calcJurosCompostos($invest_init,TAXA_GAIN,$meses);

        $lucro = $calcular_juros - $invest_init;

        $lucro_taxado = $lucro - ($lucro * $tax);

        $token = getToken(5);
        $dataSaque = [
            'id' => $token,
            'investment_id' => $data['investiment_id'],
            'valor_investido' => $investimento->transaction_ammount,
            'total_anos' => $calcular_diferenca_entre_datas['y'],
            'total_meses' => $meses,
            'taxa_sobre_lucro' => $tax,
            'lucro' => $lucro,
            'valor_descontado_do_lucro' => $lucro - $lucro_taxado,
            'lucro_ja_taxado' => $lucro - ( $lucro - $lucro_taxado),
            'saldo_final' => $lucro_taxado + $investimento->transaction_ammount,
            'transaction_date'=> date('Y-m-d')

        ];


        // Cria saque
        $this->modelWithdrawal->insert($dataSaque);
        // Atualiza saldo do usuario
        $this->modelUser->set('user_balance','user_balance +'. ($lucro_taxado + $investimento->transaction_ammount),false)->update(USER_ID);
        // Apaga ivestimento já resgatado
        $this->modelInvestiment->delete($data['investiment_id']);

        $response = [
            'id-saque' => $token,
            'investment_id' => $data['investiment_id'],
            'valor_investido' => "R$ " . moneyReal($investimento->transaction_ammount),
            'total_anos' => $calcular_diferenca_entre_datas['y'],
            'total_meses' => $meses,
            'taxa_sobre_lucro' => $tax * 100 . "%",
            'lucro' => "R$ " . moneyReal($lucro),
            'valor_descontado_do_lucro' => "R$ " . moneyReal($lucro - $lucro_taxado),
            'lucro_ja_taxado' => "R$ " . moneyReal($lucro_taxado),
            'saldo_final' => "R$ " . moneyReal($lucro_taxado + $investimento->transaction_ammount)

        ];

        return $this->respondCreated($response,'Saque criado com sucesso.');
    }

    protected function validateRequest($data,$ivestiment)
    {
        if (!isset($data['investiment_id']) or strlen($data['investiment_id']) < 1):
            return [
                'success' => false,
                'body' => "É necessário informar o ID do investimento para saque.",
            ];
        endif;
        if (!isset($data['data_withdrawal']) or strlen($data['data_withdrawal']) != 10 ):
            return[
                'success' => false,
                'body' => "A data de saque é um campo obrigatório"
            ];
        endif;

        if (!validateDate($data['data_withdrawal'],'Y-m-d') or $data['data_withdrawal'] > date('Y-m-d') or $data['data_withdrawal'] < '2015-01-01' or $data['data_withdrawal'] < $ivestiment->transaction_date):
            return[
                'success' => false,
                'body' => "A data deve ser válida, no formato YYYY-mm-dd e deverá ser igual ou inferior a data atual " . date('Y-m-d')
            ];
        endif;

        return[
            'success' => true,
        ];

    }
}
