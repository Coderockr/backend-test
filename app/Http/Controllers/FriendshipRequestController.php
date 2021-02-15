<?php

namespace App\Http\Controllers;

use App\Models\FriendshipRequest;
use Illuminate\Http\Request;

class FriendshipRequestController extends Controller
{
    public function index()
    {
        return auth()->user()->friendship_requests;
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'friend_id' => ['required', 'exists:App\Models\User,id'],
        ]);

        $user = auth()->user();
        $friendshipRequest = $user->friendship_requests()->create($data);

        return response()->json($friendshipRequest, 201);
    }

    public function accept(FriendshipRequest $friendshipRequest)
    {
        $user = auth()->user();
        $user->friendships()->create(['friend_id' => $friendshipRequest->friend_id]);

        $friendshipRequest->delete();
    }
}
