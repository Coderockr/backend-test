<?php

namespace App\Http\Controllers;

use App\Models\Friend;

class FriendController extends Controller
{
    public function index()
    {
        return Friend::query()
            ->with(['user', 'friend',])
            ->get();
    }

    public function delete(Friend $friend)
    {
        $friend->delete();

        return response()->json(null, 204);
    }
}
