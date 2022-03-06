<?php

namespace App\Controller\Api;

use App\Model\Entity\Investment as EntityInvestment;

use DateTime;

class Investment
{
    public static function getInvestmentList($request)
    {
        $investmentList = [];
        $results = EntityInvestment::getInvestmentList();
        
        while($investment = $results->fetchObject(EntityInvestment::class))
        {
            $investmentList[] = [
                'id' => (int) $investment->id,
                'idInvestor' => (int) $investment->idInvestor,
                'amount' => $investment->amount,
                'withdrew' => $investment->withdrew,
                'investmentDate' => (new DateTime($investment->investmentDate))->format('Y-m-d')
            ];
        }
        return $investmentList;
    }
}