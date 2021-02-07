<?php

namespace App\Http\Controllers;

use App\Models\Friendship;

class FriendshipController extends Controller
{
    public function index()
    {
        return Friendship::query()
            ->with(['user', 'friend',])
            ->get();
    }

    public function delete(Friendship $friendship)
    {
        $friendship->delete();

        return response()->json(null, 204);
    }
}
