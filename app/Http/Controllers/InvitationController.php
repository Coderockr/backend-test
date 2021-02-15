<?php

namespace App\Http\Controllers;

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
            'email' => ['required', 'email'],
        ]);

        $user = auth()->user();
        $invitation = $user->invitations()->create($data);

        return response()->json($invitation, 201);
    }
}
