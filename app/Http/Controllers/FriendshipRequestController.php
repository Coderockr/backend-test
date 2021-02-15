<?php

namespace App\Http\Controllers;

use App\Models\FriendshipRequest;
use App\Scopes\UserScope;
use Illuminate\Http\Request;

class FriendshipRequestController extends Controller
{
    public function index()
    {
        return FriendshipRequest::query()
            ->withoutGlobalScope(UserScope::class)
            ->where('friend_id', auth()->user()->id)
            ->get();
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

    public function accept($id)
    {
        $friendshipRequest = FriendshipRequest::query()
            ->withoutGlobalScope(UserScope::class)
            ->find($id);

        $user = auth()->user();
        $user->friendships()->create(['friend_id' => $friendshipRequest->user_id]);

        $friendshipRequest->delete();
    }
}
