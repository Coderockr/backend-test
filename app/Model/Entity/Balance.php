<?php

namespace App\Model\Entity;

use Database\Repository;
use Database\Transaction;

class Balance
{

    public $id;
    public $idInvestor;
    public $idInvestment;
    public $currentBalance;
    public $gain;
    public $balanceDate;
    public $createdAt;

    
    public static function getBalanceList($where = null, $order = null, $limit = null, $fields = '*')
    {
        Transaction::open();
        $results = (new Repository('balance'))->select($where, $order, $limit, $fields);
        Transaction::close();

        return $results;
    }


    public function getBalance($id)
    {
        return self::getBalanceList('id = '.$id)->fetchObject(self::class);
    }


    public static function getBalanceByInvestment($idInvestment, $idInvestor)
    {
        return self::getBalanceList(
            "idInvestment = {$idInvestment} AND idInvestor = {$idInvestor}"
            , 'balanceDate DESC', '1'
        )->fetchObject(self::class);
    }

    
    public function insert()
    {
        $this->id = (new Repository('balance'))->insert([
            'idInvestor' => $this->idInvestor,
            'idInvestment' => $this->idInvestment,
            'currentBalance' => $this->currentBalance,
            'gain' => $this->gain,
            'balanceDate' => $this->balanceDate->format('Y-m-d'),
            'createdAt' => (new \DateTime())->format('Y-m-d H:i:s')
        ]);

        return $this->id ? $this->id : false;
    }
}