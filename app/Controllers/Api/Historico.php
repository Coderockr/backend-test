<?php

namespace App\Controllers\Api;


use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class Historico extends Controller
{
    protected $helpers = ['apoio'];
    protected $modelUser;
    protected $modelInvestimentos;
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
        $investimentos = $this->modelInvestiment->orderBy('transaction_date','DESC')->get()->getResult();

        $data = [];
        $data['saldo_em_conta'] = "R$ " . moneyReal($this->modelUser->get()->getRow()->user_balance);

        $data['saldo_investido'] = 0;

        foreach ($investimentos as $investimento):
            $saque =  $this->modelWithdrawal->where('investment_id',$investimento->id)->get()->getRow();

            if (!$saque){
                $data['saldo_investido'] += $investimento->transaction_ammount;
            }

            $data[] = [
                'investimento' => $investimento,
                'retirada' => $saque
            ];
        endforeach;
        $data['saldo_investido'] = "R$ " . moneyReal($data['saldo_investido']);

        return $this->respond($data,201);

    }


}