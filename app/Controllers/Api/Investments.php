<?php

namespace App\Controllers\Api;

use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

class Investments extends Controller
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
        responseJson([
            'success' => true,
            'body' => $this->modelInvestiment->getWhere(['id' => USER_ID])->getResult()
        ]);
    }
    public function create()
    {
        $data = $this->request->getPost();

        $check_data = $this->validateRequestArray($data);

        if ($check_data['success'] == false):
            return $this->failNotFound($check_data['body'],404);
        endif;

        $produto = $this->modelProducts->getProductByid($data['product_id']) ;
        if (!$produto):
            return $this->failNotFound("Não foi encontrado o produto com especificado.",404);
        endif;

        if ($produto->investment_min_value > $data['ammount']):
            return $this->failNotFound("O valor mínimo para investimento deste produto deve ser R$ " . moneyReal($produto->investment_min_value),404);
        endif;
        $balance = $this->modelUser->asObject()->select('user_balance')->get()->getRow();
        if ( $balance->user_balance < $data['ammount']):
            return $this->failNotFound("Saldo insuficiente. Saldo atual: R$ " . moneyReal($balance->user_balance),404);
        endif;

        try {
            $array = [
                'id' => getToken(5),
                'transaction_user_id' => USER_ID,
                'transaction_investiment_id' => $data['product_id'],
                'transaction_type' => "incoming",
                'transaction_ammount' =>  $data['ammount'],
                'transaction_date' => $data['data_start'] ?? date('Y-m-d')
            ];

            $this->modelInvestiment->insert($array);
            $this->modelUser->set('user_balance','user_balance -'. $data['ammount'],false)->update(USER_ID);
            return $this->respondCreated([
                'body' => "Investimento criado"
            ]);
        }catch (\Exception $e){
            return $this->fail("Erro ao criar investimento, entre em contato com nosso suporte" ,500);
        }

    }

    /**
     * @param array $data
     * @return array|bool[]
     */
    protected function validateRequestArray(array $data) : array
    {
        if (!isset($data['product_id']) or strlen($data['product_id']) < 1):
            return [
                'success' => false,
                'body' => "É necessário informar o ID do produto para investimento.",
            ];
        endif;

        if (!isset($data['data_start']) or strlen($data['data_start']) != 10 ):
            return[
                'success' => false,
                'body' => "A data de investimento é campo obrigatório"
            ];
        endif;

        if (!validateDate($data['data_start'],'Y-m-d') or $data['data_start'] < '2015-01-01'):
            return[
                'success' => false,
                'body' => "A data deve ser válida, no formato YYYY-mm-dd e deverá ser igual ou superior a 2015-01-01"
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
