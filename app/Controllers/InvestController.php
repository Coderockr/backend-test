<?php

namespace App\Controllers;

use App\Models\Investments;

class InvestController
{
    private int $id;
    private string $token;

    public function __constructor(int $id, string $token)
    {
        $this->id = $id;
        $this->token = $token;
    }

    public function Create(int $user_id, float $value, string $date): array
    {

        return Investments::Create($value,$user_id, $date);
    }
}