<?php

namespace App\Services;

use App\Models\Investment;
use App\Models\Owner;

class InvestmentService
{
    public function create(array $data)
    {
        Investment::create([
            'initial_amount' => $data['amount'],
            'creation_date' => $data['creation_date'],
            'owner_id' => $data['owner_id']
        ]);

        return response()->json(['Created Investment!'], 201);
    }
}
