<?php

namespace App\Controller\Api;

use App\Controller\Api\BasicAuth;

use App\Model\Entity\Investment as EntityInvestment;
use App\Model\Entity\Balance as EntityBalance;

use Database\Transaction;
use Database\Pagination;

use DateInterval;
use DateTime;
use Exception;

class Investment extends Api
{
    private static function checkAutentication(&$request)
    {
        return BasicAuth::basicAuth($request);
    }


    private static function getInvestmentRecords($request, &$pagination)
    {
        $investments = [];
        $idInvestor = $request->investor->id;

        $investmentsQuantity = EntityInvestment::getInvestmentList(
            'idInvestor = '.$idInvestor, null, null, 'COUNT(*) as quantity'
        )->fetchObject()->quantity;

        $queryParams = $request->getQueryParams();
        $currentPage = $queryParams['page'] ?? 1;
        $pagination = new Pagination($investmentsQuantity, $currentPage, 5);

        $results = EntityInvestment::getInvestmentList('idInvestor = '.$idInvestor, 'id DESC', $pagination->getLimit());

        while($investment = $results->fetchObject(EntityInvestment::class))
        {
            $investments[] = [
                'id' => (int) $investment->id,
                'idInvestor' => (int) $investment->idInvestor,
                'amount' => $investment->amount,
                'withdrew' => $investment->withdrew,
                'investmentDate' => (new DateTime($investment->investmentDate))->format('Y-m-d')
            ];
        }

        return $investments;
    }
    
    
    public static function getInvestmentList($request)
    {
        self::checkAutentication($request);

        return [
            'investments' => self::getInvestmentRecords($request, $pagination),
            'pagination' => parent::getPagination($request, $pagination)
        ];
    }


    public static function getInvestmentOverview($request, $id)
    {
        self::checkAutentication($request);

        $investmentOverViewList = [];
        $idInvestor = $request->investor->id;

        $results = EntityInvestment::getInvestmentOverview(
            "idInvestor = {$idInvestor} AND idInvestment = {$id}"
        );        
        
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
        
        if(!$investmentOverViewList)
        {
            throw new Exception("Data not found", 404);
        }

        return $investmentOverViewList;
    }


    public static function setNewInvestment($request)
    {
        self::checkAutentication($request);
        
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
        $investment->idInvestor = $request->investor->id;
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

            return [
                'id' => $investment->id,
                'idInvestor' => $investment->idInvestor,
                'amount' => $investment->amount,
                'withdrew' => (bool) $investment->withdrew,
                'investmentDate' => $investment->investmentDate->format('Y-m-d'),
                'investmentOverview' => $request->getRouter()->getCurrentUrl()."/{$investment->id}"
            ];
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


    public static function setInvestmentWithdrawal($request, $id)
    {
        self::checkAutentication($request);

        $idInvestor = $request->investor->id;
        $investment = EntityInvestment::getInvestment($id, $idInvestor);
        $investmentOverview = EntityInvestment::getInvestmentOverview($id, $idInvestor);

        try 
        {
            Transaction::open();

            $investment->withdrew = 1;
            $investment->investmentDate = new DateTime($investment->investmentDate);
            $investment->update();

            $investmentOverview = $investmentOverview->fetch(\PDO::FETCH_ASSOC);
            $lastInvestmentUpdate = new DateTime($investmentOverview['lastInvestmentUpdate']);
            $currentDate = new DateTime();
            $interval = (int) ($lastInvestmentUpdate->diff($currentDate))->m;

            $balance = new EntityBalance;
            $balance->idInvestor = $investment->idInvestor;
            $balance->idInvestment = $investment->id;

            if($interval > 0)            
            {                
                $balance->currentBalance = $investmentOverview['currentAmount'];
                $balance->balanceDate = $lastInvestmentUpdate;

                for($i = 1; $i <= $interval; $i++)
                {
                    $initialAmount = $balance->currentBalance;
                    $balance->currentBalance = $investment->calcExpectedAmount($balance->currentBalance);
                    $balance->gain = $balance->currentBalance - $initialAmount;
                    $balance->balanceDate = $balance->balanceDate->add(new DateInterval('P1M'));
                    $balance->insert();
                }
            }

            $investment->amount = (float) number_format($balance->currentBalance, 2, '.', '');

            $balance->currentBalance = 0;
            $balance->gain = 0;
            $balance->balanceDate = new DateTime();            
            $balance->insert();

            Transaction::close();

            return [
                'id' => $investment->id,
                'idInvestor' => $investment->idInvestor,
                'amount' => $investment->amount,
                'withdrew' => (bool) $investment->withdrew,
                'investmentDate' => $investment->investmentDate->format('Y-m-d'),
                'investmentOverview' => $_SERVER['APP_URL']."/api/v1/investment/{$investment->id}"
            ];
        }
        catch (Exception $e) {
            Transaction::rollback();

            throw new Exception(
                "The withdrawal could not be registered. {$e->getMessage()}"
                , 500
            );
        }
    }
}