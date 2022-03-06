<?php

namespace App\Controller\Api;

use App\Model\Entity\Investment as EntityInvestment;
use App\Model\Entity\Balance as EntityBalance;
use Database\Transaction;

use DateInterval;
use DateTime;
use Exception;

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


    public static function setNewInvestment($request)
    {
        $postVars = $request->getPostVars();

        $investmentDate = new DateTime($postVars['investmentDate']);
        $currentDate = new DateTime("now");

        if ($investmentDate > $currentDate)
        {
            throw new \Exception('The investment date cannot be later than today', 400);
        }

        $interval = $investmentDate->diff($currentDate);
        $interval = (int) $interval->m;

        $investment = new EntityInvestment;
        $investment->idInvestor = $postVars['idInvestor'];
        $investment->amount = $postVars['amount'];
        $investment->withdrew = 0;
        $investment->investmentDate = $investmentDate;
        
        try
        {
            Transaction::open();
        
            $investment->insert();
            
            $balance = new EntityBalance;            
            $balance->idInvestor = $investment->idInvestor;
            $balance->idInvestment = $investment->id;
            $balance->currentBalance = $investment->amount;
            $balance->gain = 0;
            $balance->balanceDate = $investment->investmentDate;
            $balance->insert();

            if($interval > 0)
            {
                for($i = 1; $i <= $interval; $i++)
                {
                    $initialAmount = $balance->currentBalance;
                    $balance->currentBalance = $investment->calcExpectedAmount($balance->currentBalance);
                    $balance->gain = $balance->currentBalance - $initialAmount;
                    $balance->balanceDate = $balance->balanceDate->add(new DateInterval('P1M'));
                    $balance->insert();
                }
            }
            
            Transaction::close();
        }
        catch(Exception $e)
        {
            Transaction::rollback();

            throw new Exception(
                "The investment could not be registered. {$e->getMessage()}"
                , 500
            );
        }
        
    }
}