<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;



class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function create(Request $request)
    { // owner, date, amount
        // dd($request);
        return [['created' => "true"]];
    }

    public function list()
    {
        $users = User::paginate(5);

        return $users;
    }
    public function test2(Request $request)
    {
        dd(
            'request2',
            $request
        );
    }

    //
}
