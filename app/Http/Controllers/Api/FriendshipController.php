<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\FriendshipCollection;
use App\Http\Resources\FriendshipInvitationCollection;
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
    /**
     * Handles with the invitation of user friendship by email (as well as their validation and creation)
     *
     * @param $email
     * @return \Illuminate\Http\JsonResponse
     */
    public function invite($email)
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

    /**
     * Display a pending list of the friendship invitations of the user.
     *
     * @return FriendshipInvitationCollection
     */
    public function pending(Request $request)
    {
        $user = auth('api')->user();

        $collection = FriendshipInvitation::ofGuest($user->id)
                                          ->pending()
                                          ->latest()
                                          ->get();

        return new FriendshipInvitationCollection($collection);
    }


    /**
     * Call to the update status method to confirm an invitation
     *
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirm($user_id)
    {
        return $this->updateStatus($user_id, 'confirm');
    }

    /**
     * Call to the update status method to reject an invitation
     *
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject($user_id)
    {
        return $this->updateStatus($user_id, 'rejected');
    }

    /**
     * Handles with confirm or reject the user friendship invitations
     *
     * @param $user_id
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus($user_id, $status)
    {
        try {
            $invite = FriendshipInvitation::where('user_id', $user_id)->where('guest_id', auth('api')->user()->id)->firstOrFail();

            // User can only acccess their own pending invitations.
            if ($invite->status == 'pending') {

                // Update the invitation status
                $invite->update(['status' => $status]);

                if ($status == 'confirm') {
                    $msg = 'confirmed';

                    // Register the Friendship
                    Friendship::create([
                        'user_id' => $invite->user_id,
                        'friend_id' => $invite->guest_id
                    ]);
                } else {
                    $msg = $status;
                }

                return $this->responseResourceUpdated('Friendship invitation ' . $msg . '.');
            } else {
                return $this->responseUnauthorized();
            }
        } catch (Exception $e) {
            return $this->responseServerError('Error ' . ($status == 'rejected' ? 'rejecting' : 'confirming') . ' the invitation.');
        }
    }

    /**
     * Handles with removing a user friendship.
     *
     * @param $friend_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($friend_id)
    {
        try {
            $friendship = Friendship::ofUs([auth('api')->user()->id, $friend_id])->firstOrFail();
            if ($friendship) {
                $friendship->delete();
                return $this->responseSuccess('Friendship removed');
            } else {
                return $this->responseUnauthorized();
            }
        } catch (Exception $e) {
            return $this->responseServerError('Error undoing the friendship.');
        }
    }

    /**
     * Return the list of user friends
     *
     * @return FriendshipCollection
     */
    public function friends()
    {
        return new FriendshipCollection( auth('api')->user()->friends );
    }
}
