<?php

namespace App\Models;

use WilliamCosta\DatabaseManager\Database;

class Investments
{
    public static function Create(string $value, int $user_id, string $date): array
    {

        if(!Users::isUser($user_id)) {
            return [
                'status' => false,
                'message' => 'User not registered'
            ];
        }

        $obInvest = new Database('investments');

        $id = $obInvest->insert([
           'user_id' => $user_id,
            'value' => $value,
            'created_at' => $date
        ]);

        return [
            'status' => 'ok',
            'message' => 'Investment created successfully',
            'details' => [
                'investment_id' => $id,
                'created_at' => $date
            ]
        ];
    }

    public function getInvestmentById(int  $id)
    {
        $obinvestment = new Database('investments');
        $res = $obinvestment->select('id ='. $id);
       return $res->fetchObject(self::class);
    }
}