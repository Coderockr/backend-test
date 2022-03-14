<?php

namespace App\Repositories;

use App\Models\Withdrawal;
use DateTime;

class WithdrawalRepository
{
    protected $entity;

    public function __construct(Withdrawal $model, InvestimentRepository $investiment)
    {
        $this->entity = $model;
        $this->investiment = $investiment;
    }

    public function saveWithdrawal(array $data)
    {
        $idInvestiment = $data['investiment_id'];
        $investiment = $this->investiment->getInvestiment($idInvestiment);
        
        if($investiment->withdraw == "y"){
            return [
                'status'  => 'error',
                'message' => 'Withdrawal has already been carried out for this investment'
            ];
        }

        $verifyInvestimentDateInvert = self::getInvestimentTimeYear($investiment->investiment_date);
        $verifyDateWithdrawal = self::verifyDateWithdrawal($investiment->investiment_date, $data['withdraw_date']);

        if ($verifyInvestimentDateInvert->invert == true or $verifyDateWithdrawal == true) {
            return [
                'status'  => 'error',
                'message' => 'The withdrawal date cannot be earlier than the investment date or later than today.'
            ];
        }

        $currentValue = $this->investiment->getCurrentValue($investiment->value, $investiment->investiment_date);
        $income =  (float) number_format($currentValue - $investiment->value, 2, '.', '');
        $investimentTime = self::getInvestimentTimeYear($investiment->investiment_date);
        $tax = self::getTax($income, (int) $investimentTime->y);
        $value = $currentValue - $tax;
        $data['value'] = number_format($value, 2, '.', '');
        $data['value_no_tax'] = number_format($currentValue, 2, '.', '');
        $data['income'] = $income;

        $dataInvestiment = [
            'user_id' => $data['user_id'],
            'withdraw' => 'y',
        ];

        $this->investiment->update($dataInvestiment, $idInvestiment);
        return $this->entity->create($data);
    }

    public static function getTax(float $income, string $investimentTime)
    {
        if ($investimentTime < 1) {
            return $income * 0.225;
        } else if ($investimentTime > 1 and $investimentTime <= 2) {
            return $income * 0.185;
        } else {
            return $income * 0.15;
        }
    }

    public static function getInvestimentTimeYear(string $date)
    {
        $dateInvestiment = new DateTime($date);
        $dateNow = new DateTime(date("Y-m-d"));

        return $dateInvestiment->diff($dateNow);
    }

    public static function verifyDateWithdrawal(string $dateInvestiment, string $dateWithdrawal)
    {
        $result = false;
        $dateNow = date("Y-m-d");
        if ((strtotime($dateInvestiment) > strtotime($dateWithdrawal)) or (strtotime($dateWithdrawal) > strtotime($dateNow))) {
            return true;
        }
        return $result;
    }
}
