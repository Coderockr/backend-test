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


    public static function getInvestmentOverview($request, $id)
    {
        $investmentOverViewList = [];
        $results = EntityInvestment::getInvestmentOverview("idInvestment = {$id}");
        
        while($investment = $results->fetch(\PDO::FETCH_ASSOC))
        {            
            $investmentDate = new DateTime($investment['investmentDate']);
            $lastInvestmentUpdate = new DateTime($investment['lastInvestmentUpdate']);
            $initialAmount = (float) number_format($investment['initialAmount'], 2, '.', '');
            $currentAmount = (float) number_format($investment['currentAmount'], 2, '.', '');
            $currentGain = (float) number_format($investment['totalGain'], 2, '.', '');

            $expectedAmount = (float) number_format(
                EntityInvestment::calcExpectedAmount($currentAmount)
                , 2, '.', ''
            );

            $expectedGain = (float) number_format($expectedAmount - $currentAmount + $currentGain, 2, '.', '');

            $investmentOverViewList[] = [
                'idInvestment' => (int) $investment['idInvestment'],
                'investmentDate' => $investmentDate->format('Y-m-d'),
                'lastInvestmentUpdate' => $lastInvestmentUpdate->format('Y-m-d'),
                'initialAmount' => $initialAmount,
                'currentAmount' => $currentAmount,
                'currentGain' => $currentGain,
                'expectedAmount' => $expectedAmount,
                'expectedGain' => $expectedGain,
                'withdrew' => (bool) $investment['withdrew']
            ];
        }
        return $investmentOverViewList;
    }
}