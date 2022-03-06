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


    public static function calcExpectedAmount($currentAmount, $interval = 1)
    {
        $expectedAmount = 0;

        // The period for calculating expected gains is one month by default 
        $expectedAmount = $currentAmount * (1 + (0.52/100) * $interval);

        return $expectedAmount;
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