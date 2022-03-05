<?php

namespace App\Controllers\Validation;

class CPF
{
    public static function Verify(string $cpf): bool {
        $cpf = preg_replace('/\D/','', $cpf);
        if(strlen($cpf) != 11) {
            return false;
        }
        $cpfValidation = substr($cpf, 0, 9);
        $cpfValidation .= self::Calculate($cpfValidation);
        $cpfValidation .= self::Calculate($cpfValidation);

        return $cpfValidation == $cpf;
    }
    private static function Calculate($base): int
    {
        $length = strlen($base);
        $multiplier = $length + 1;
        $sum = 0;
        for($i = 0; $i < $length; $i++){
            $sum += $base[$i] * $multiplier;
            $multiplier--;
        }
        $result = $sum % 11;

        return $result > 1 ? 11 - $result : 0;
    }
}