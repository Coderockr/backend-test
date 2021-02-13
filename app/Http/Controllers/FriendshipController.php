<?php

namespace App\Http\Controllers;

use App\Models\Friendship;

class FriendshipController extends Controller
{
    public function index()
    {
        return auth()->user()->friendships;
    }

    public function delete(Friendship $friendship)
    {
        $friendship->delete();

        return response()->json(null, 204);
    }
}
