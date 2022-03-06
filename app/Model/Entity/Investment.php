<?php

namespace App\Model\Entity;

use Database\Transaction;
use Database\Repository;
use DateTime;

class Investment
{
    public $id;
    public $idInvestor;
    public $amount;
    public $withdrew;
    public $investmentDate;
    public $createdAt;
    public $updatedAt;

    public static function getInvestmentList($where = null, $order = null, $limit = null, $fields = '*') 
    {
        Transaction::open();
        $result = (new Repository('investment'))->select($where, $order, $limit, $fields);
        Transaction::close();

        return $result;
    }


    public static function getInvestment($id)
    {
        return self::getInvestmentList('id = '.$id)->fetchObject(self::class);
    }


    public function insert()
    {
        $this->id = (new Repository('investment'))->insert([
            'idInvestor' => $this->idInvestor,
            'amount' => $this->amount,
            'withdrew' => $this->withdrew,
            'investmentDate' => $this->investmentDate,
            'createdAt' => new DateTime(),
            'updatedAt' => new DateTime()
        ]);

        return $this->id ? $this->id : false;
    }
}