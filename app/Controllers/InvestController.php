<?php

namespace App\Controllers;

use App\Models\Investments;
use App\Models\Users;

class InvestController
{
    public int $id;
    public string $token;

    public function __construct(int $id, string $token)
    {
        $this->id = $id;
        $this->token = $token;
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
        if($dateVerify > strtotime(date('Y-m-d H:i:s'))) {
            return [
                'status' => false,
                'message' => 'The investment date is incorrect'
            ];
        }
        return Investments::Create($value,$this->id, $date);
    }

    public function view($id): array
    {
        if(!Investments::isInvestment($id)){
            return [
              'status' => false,
              'message' => 'Investment not found'
            ];
        }
        $getInvestment = Investments::getInvestmentById($id);
        return [
            'status' => 'ok',
            'initial_investment' => $getInvestment->value,
            'current_investment' => Investments::getInvestmentCurrent($id),
            'income' => Investments::getInvestmentCurrent($id, 'income')

        ];

    }
}