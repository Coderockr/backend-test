<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function index()
    {
        return auth()->user()->invitations;
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'user_id' => ['required', 'exists:App\Models\User,id'],
            'email' => ['required', 'email'],
        ]);

        $invitation = Invitation::create($data);

        return response()->json($invitation, 201);
    }
}
