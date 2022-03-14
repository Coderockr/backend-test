<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Simulate extends BaseController
{
    protected $helpers = ['apoio'];
    protected $modelInvestiment;
    protected $modelProducts;
    protected $modelUser;
    use ResponseTrait;

    public function __construct()
    {
        $this->modelInvestiment = new \App\Models\Investments();
        $this->modelProducts = new \App\Models\Products();
        $this->modelUser = new \App\Models\Users();
    }

    public function index()
    {

        $data = $this->request->getVar();

        $check_data = $this->validateRequestArray($data);
        if ($check_data['success'] == false):
            return $this->failNotFound($check_data['body'],404);
        endif;

        $produto = $this->modelProducts->getProductByid($data['product_id']) ;
        if (!$produto):
            return $this->failNotFound("Não foi encontrado o produto especificado.",404);
        endif;

        if ($produto->investment_min_value > $data['ammount']):
            return $this->failNotFound("O valor mínimo para investimento deste produto deve ser R$ " . moneyReal($produto->investment_min_value),404);
        endif;

        $balance = $this->modelUser->asObject()->select('user_balance')->get()->getRow();
        if ( $balance->user_balance < $data['ammount']):
            return $this->failNotFound("Saldo insuficiente. Saldo atual: R$ " . moneyReal($balance->user_balance),404);
        endif;
        $calcular_diferenca_entre_datas = diffDates($data['data_start'],$data['data_end']);
        $meses = $calcular_diferenca_entre_datas['y'] * 12 + $calcular_diferenca_entre_datas['m'];

        if($meses <= 12 ):
            $tax = 0.225;
        elseif ($meses > 12 && $meses <= 24):
            $tax = 0.185;
        elseif ($meses > 24):
            $tax = 0.15;
        endif;

        $calcular_juros = calcJurosCompostos($data['ammount'],TAXA_GAIN,$meses);
        $lucro = $calcular_juros['montante'] - $data['ammount'];
        $lucro_taxado = $lucro - ($lucro * $tax);
        $resposta = [
            'valor_investido' => "R$ " . moneyReal($data['ammount']),
            'total_anos' => $calcular_diferenca_entre_datas['y'],
            'total_meses' => $meses,
            'taxa_sobre_lucro' => $tax * 100 . "%",
            'lucro' => "R$ " . moneyReal($lucro),
            'valor_descontado_do_lucro' => "R$ " . moneyReal($lucro - $lucro_taxado),
            'lucro_ja_taxado' => "R$ " . moneyReal($lucro_taxado),
            'saldo_final' => "R$ " . moneyReal($lucro_taxado + $data['ammount'])

        ];
        return $this->respond($resposta,200);


    }
    protected function validateRequestArray(array $data) : array
    {

        if (!isset($data['product_id']) or strlen($data['product_id']) < 1):
            return [
                'success' => false,
                'body' => "É necessário informar o ID do produto para investimento.",
            ];
        endif;

        if (!isset($data['data_start']) or !isset($data['data_end']) or strlen($data['data_start']) != 10 or strlen($data['data_end']) != 10):
            return[
                'success' => false,
                'body' => "As datas devem ser válidas, no formato YYYY-mm-dd e deverá estar entre os anos de 2015 e 2030"
            ];
        endif;
        if (!validateDate($data['data_start'],'Y-m-d') or !validateDate($data['data_end'],'Y-m-d')):
            return[
                'success' => false,
                'body' => "As datas de inicio e fim devem ser datas válidas"
            ];
        endif;

        if($data['data_end'] < $data['data_start']):
            return[
                'success' => false,
                'body' => "A data final deve ser maior que a data inicial do investimento"
            ];
        endif;

            if($data['data_end'] < "2015-01-01" or $data['data_start'] < '2015-01-01' ):
            return[
                'success' => false,
                'body' => "A data de incio e a data final devem ser superiores à 01-01-2015"
            ];
        endif;

        if($data['data_end'] > "2030-12-31"  ):
            return[
                'success' => false,
                'body' => "A data final deve ser menor que 31-12-2030"
            ];
        endif;

        if (!isset($data['ammount']) or floatval($data['ammount']) < 0):
            return[
                'success' => false,
                'body' => "É necessário informar o valor para o investimento e deverá ser um número positivo"
            ];
        endif;

        return[
            'success' => true,
        ];
    }
}
