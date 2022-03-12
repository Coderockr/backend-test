<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\User as UserResource;

class UserController extends Controller
{
    //
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Request $request
     * @return \App\Models\User
     */
    protected function creation(Request $request)
    {
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        
        // validation to create a new investor
        $validate = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|unique:users|max:255',
            'password'   => 'required|string|min:8|confirmed'
        ]);

        // show a error in case of validation fail
        if ($validate->fails()) {
            return response(json_encode($validate->errors()), 400)
                            ->header('Content-Type', 'application/json');
        }

        // save a new investment
        if( $user->save() ){
          return new UserResource($user);
        }
    }
}
