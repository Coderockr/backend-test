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
        $id = Owner::where('email', $data['owner'])->first()->id;
        return response()->json([Investment::where('owner_id',$id)->paginate(2)]);
    }
}
