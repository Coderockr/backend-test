<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Events\RegisterRequestCreated;
use App\Models\Friendship;
use App\Models\FriendshipInvitation;
use App\Models\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FriendshipController extends ApiController
{
    public function inviteFriendByEmail($email)
    {
        $email = trim($email);

        // Current user
        $me = auth('api')->user();

        // Validate email param
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|string|email|max:175|not_in:' . $me->email
        ]);

        // If validations fails
        if ($validator->fails()) {
            return $this->responseUnprocessable($validator->errors()->toArray());
        }

        // Check if the user is already registered
        $friend = User::where('email', $email)->first();

        if ($friend) {
            // Verify if users already friends
            $alreadyFriends = Friendship::ofUs([$me->id, $friend->id])->first();

            if (!$alreadyFriends) {
                // Verify if invitation already exists
                $alreadyInvited = FriendshipInvitation::pending()->ofUs([$me->id, $friend->id])->first();

                if (!$alreadyInvited) {
                    try {
                        // Store the FriendshipInvitation
                        $invite = $this->createFriendshipInvitation($me->id, $friend->id);

                        return $this->responseSuccess('Friendship invitation sent.');
                    } catch (Exception $e) {
                        return $this->responseServerError('Friendship invitation error.');
                    }
                } else {
                    return $this->responseUnprocessable(['Invitation already exists.']);
                }
            } else {
                return $this->responseUnprocessable(['You\'re already friends.']);
            }

        } else {
            // Verify if invitation already exists
            $alreadyInvited = RegisterRequest::ofUser($me->id)->ofEmail($email)->first();

            if (!$alreadyInvited) {
                try {
                    // Store the RegisterRequest
                    $request = $this->createRegisterRequest($me->id, $email);

                    // Fake Send the email
                    event(new RegisterRequestCreated($request));

                    return $this->responseSuccess('Register request sent.');
                } catch (Exception $e) {
                    return $this->responseServerError('Register request error.');
                }
            } else {
                return $this->responseUnprocessable(['Register request already exists.']);
            }
        }
    }

    /**
     * Create a new FriendshipInvitation instance after the validation.
     *
     * @param $user_id
     * @param $guest_id
     * @return mixed
     */
    public function createFriendshipInvitation($user_id, $guest_id) {
        return FriendshipInvitation::create([
            'user_id' => $user_id,
            'guest_id' => $guest_id,
            'status' => 'pending'
        ]);
    }

    /**
     * Create a new RegisterRequest instance after the validation.
     *
     * @param $user_id
     * @param $email
     * @return mixed
     */
    public function createRegisterRequest($user_id, $email) {
        return RegisterRequest::create([
            'user_id' => $user_id,
            'email' => $email
        ]);
    }
}
