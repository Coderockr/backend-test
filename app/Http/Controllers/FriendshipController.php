<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Scopes\UserScope;

class FriendshipController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return Friendship::query()
            ->withoutGlobalScope(UserScope::class)
            ->where('user_id', $user->id)
            ->orWhere('friend_id', $user->id)
            ->get();
    }

    public function delete(Friendship $friendship)
    {
        $friendship->delete();

        return response()->json(null, 204);
    }
}
