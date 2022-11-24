<?php

namespace App\Services;

use App\Models\Investment;
use App\Models\Owner;

class OwnerService
{
    public function create(array $data)
    {
        Owner::create([
            'name' => $data['name'],
            'email' => $data['email']
        ]);

        return response()->json(['Owner registerd!'], 201);
    }

    public function showInvestments(array $data)
    {   
        return response()->json([Investment::where('owner_id',$data['owner'])->paginate(2)]);
    }
}
