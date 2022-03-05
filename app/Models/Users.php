<?php

namespace App\Models;

use WilliamCosta\DatabaseManager\Database;

class Users
{
    public static function Create(string $name, int $cpf): array
    {

        $obUser = new Database('users');

        $res = $obUser->select('cpf ='. $cpf);
        if($res->fetchObject(self::class)) {
            return [
                'status' => false,
                'message' => 'This user has already been created, use your ID and Token to use the API'
            ];
        }

        $token = MD5($cpf); // For example Only
        $id = $obUser->insert([
            'name' => $name,
            'cpf' => $cpf,
            'token' => $token
        ]);

        return [
            'status' => 'ok',
            'message' => 'User created successfully',
            'credentials' => [
                'id' => $id,
                'token' => $token
            ]
        ];
    }

    public static function isUser($id)
    {
        $obUser = new Database('users');

        $res = $obUser->select('id ='. $id);
        if($res->fetchObject(self::class)) {
            return true;
        }
        return false;
    }
}