<?php

namespace App\Helpers;


class Validator
{

    public static function validDate(string $date)
    {
        $dateArr = explode('/', $date);
        if (!checkdate($dateArr[1], $dateArr[0], $dateArr[2])) {
            throw new \Exception("Data inválida: {$date}");
        }
        return true;
    }

    public static function requireValidator($fields, $data)
    {
        foreach ($fields as $key => $value) {
            if (!array_key_exists($key, $data) || (is_string($data[$key]) && trim($data[$key]) === '') || $data[$key] === null) {
                throw new \Exception('O campo ' . $value . ' é obrigátorio');
            }
        }
    }


}
