<?php

namespace App\Services;

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
}
