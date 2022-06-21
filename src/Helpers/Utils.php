<?php
/**
 * Created by PhpStorm.
 * User: werlich
 * Date: 13/01/20
 * Time: 17:50
 */

namespace App\Helpers;


class Utils
{

    public static function formatMoney(float $value): string
    {
        return "R$ " . number_format($value, 2, ',', '.');
    }

    public static function moneyToFloat(string $str)
    {
        $str = str_replace('R$', '', $str);
        $str = trim($str);
        $str = str_replace('.', '', $str);
        return (float)str_replace(',', '.', $str) ?? 0;
    }

    public static function onlyNumbers(string $str)
    {
        return preg_replace("/[^0-9]/", "", $str);
    }

}