<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function index()
    {
        return auth()->user()->friends;
    }
}
