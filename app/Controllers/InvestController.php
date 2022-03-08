<?php

namespace App\Controllers;

use App\Models\Investments;
use App\Models\Users;
use DateTime;

class InvestController
{
    public int $id;
    public string $token;

    public function __construct(int $id, string $token)
    {
        $this->id = $id;
        $this->token = $token;
    }

    function validateDate($date, $format = 'Y-m-d H:i:s'): bool
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function Create($value, $date): array
    {
        if(empty($this->id) or empty($value)) {
            return [
              'status' => false,
              'message' => 'User.id, user.token and value fields are required'
            ];
        }
        if(!is_numeric($value) or $value <= 0) {
            return [
              'status' => false,
              'message' => 'Your investment must be greater than 0'
            ];
        }
        if(!Users::validateToken($this->id, $this->token)){
            return [
                'status' => false,
                'message' => 'Invalid user ID or token'
            ];
        }
      $dateVerify = strtotime($date);
        if($dateVerify > strtotime(date('Y-m-d H:i:s')) or !$this->validateDate($date)) {
            return [
                'status' => false,
                'message' => 'The investment date is incorrect'
            ];
        }
        return Investments::Create($value,$this->id, $date);
    }

    public function view($id): array
    {
        if(!Users::validateToken($this->id, $this->token)){
            return [
                'status' => false,
                'message' => 'Invalid user ID or token'
            ];
        }

        if(!Investments::isInvestment($id) or !Investments::isOwner($id, $this->id)){
            return [
              'status' => false,
              'message' => 'Investment not found'
            ];
        }
        $getInvestment = Investments::getInvestmentById($id);
        return [
            'status' => 'ok',
            'id' => $id,
            'initial_investment' => $getInvestment->value,
            'current_investment' => Investments::getInvestmentCurrent($id),
            'income' => Investments::getInvestmentCurrent($id, 'income'),
            'withdrawal' => Investments::getInvestmentCurrent($id, 'withdrawal')

        ];

    }

    public function Withdrawal($id, $date): array
    {
        if(!Investments::isInvestment($id)){
            return [
                'status' => false,
                'message' => 'Investment not found'
            ];
        }
        $dateVerify = strtotime($date);

        $getInvestment = Investments::getInvestmentById($id);

        if($dateVerify > strtotime(date('Y-m-d H:i:s')) or $dateVerify < strtotime($getInvestment->created_at) or !$this->validateDate($date)) {
            return [
                'status' => false,
                'message' => 'The withdrawal date is invalid'
            ];
        }
        return [
            'status' => 'ok',
            'message' => 'Withdrawal successful',
            'initial_investment' => $getInvestment->value,
            'withdrawal' => Investments::getInvestmentCurrent($id, 'withdrawal')

        ];
    }
}