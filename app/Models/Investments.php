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

    public static function isInvestment($id): bool
    {
        $obinvestments = new Database('investments');

        $res = $obinvestments->select('id ='. $id);
        if($res->fetchObject(self::class)) {
            return true;
        }
        return false;
    }

    public static function getInvestmentById(int  $id)
    {
        $obinvestment = new Database('investments');
        $res = $obinvestment->select('id ='. $id);
       return $res->fetchObject(self::class);
    }

    public static function getInvestmentCurrent(int  $id, $type = null): string
    {
        $obinvestment = new Database('investments');
        $res = $obinvestment->select('id ='. $id);
        $result = $res->fetchObject(self::class);

        $balance = $result->value;

        $dateinitial = strtotime($result->created_at);

        $difference = strtotime(date('Y-m-d')) - $dateinitial;
        $days = floor($difference / (60 * 60 * 24));
        $calcule = floor($days/30);
        $arg = 0;

        for( ; 0 < $calcule; $calcule--) {
            $arg += 0.52/100 * $balance;
        }
        if($type == "income") {

            return number_format($arg,2);
        }

        return number_format($arg + $balance, 2, '.','');
    }
}