<?php

namespace App\Controllers;
use App\Controllers\Validation\CPF;
use App\Models\Users;


class CreateUsers
{

    public function Create($name = null, string $cpf = null): array
    {
        // CPF or name is empty
        if(empty($cpf) or empty($name)) {
            return [
                'status' => false,
                'message' => 'All fields are required'
            ];
        }
        // CPF is invalid
        if(!CPF::Verify($cpf)) {
            return [
                'status' => false,
                'message' => 'This CPF is invalid'
            ];
        }

        // Create user in database
        $cpf = preg_replace('/\D/','', $cpf);
        return Users::Create($name, $cpf);
    }
}