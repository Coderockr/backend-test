<?php

namespace App\Http\Controllers;

use App\Models\FriendshipRequest;
use Illuminate\Http\Request;

class FriendshipRequestController extends Controller
{
    public function index()
    {
        return FriendshipRequest::query()->get();
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'user_id' => ['required', 'exists:App\Models\User,id'],
            'friend_id' => ['required', 'exists:App\Models\User,id'],
        ]);

        $friendshipRequest = FriendshipRequest::create($data);

        return response()->json($friendshipRequest, 201);
    }
}
