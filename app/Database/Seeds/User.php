<?php

namespace App\Database\Seeds;

use App\Models\UserModel;
use CodeIgniter\Database\Seeder;
use ReallySimpleJWT\Token;


class User extends Seeder
{
    public function run()
    {

        $userModel = new UserModel();

        for ($i =0; $i <= 10    ; $i++){
            $token = openssl_random_pseudo_bytes(6);
            $token = bin2hex($token);

            // JWT Liby: https://github.com/RobDWaller/ReallySimpleJWT
            $userId = $token;
            $secret = 'sec!ReT423*&';
            $expiration = time() + 3600;
            $issuer = 'localhost';

            $jwt_token = Token::create($userId, $secret, $expiration, $issuer);

            $data = [
                'user_id'           => $token,
                'user_name'         => "user_{$i}",
                'user_pass'         => sha1("senha"),
                'user_balance'      => mt_rand(1000.00,5000.00),
                'user_jwt_token'    => $jwt_token,
            ];

            try {
                $userModel->insert($data);
            }catch (\Exception $e){
                echo $e->getMessage();
            }
        }
    }
}
