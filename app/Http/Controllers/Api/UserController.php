<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    public function inviteFriendByEmail($email)
    {
        $validator = Validator::make(['email' => $email], ['email' => 'required|string|email|max:175']);

        if ($validator->fails()) {
            return $this->responseUnprocessable($validator->errors()->toArray());
        }

        dd( $email, auth('api')->user() );

//        // User can only cancel their own pending events.
//        if ($event->owner_id != auth('api')->user()->id || $event->status != 'pending') {
//            return $this->responseUnauthorized();
//        }
    }
}
