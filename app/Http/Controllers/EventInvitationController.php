<?php

namespace App\Http\Controllers;

use App\Models\EventInvitation;
use Illuminate\Http\Request;

class EventInvitationController extends Controller
{
    public function index()
    {
        return auth()->user()->event_invitations;
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'event_id' => ['required', 'exists:App\Models\Event,id'],
            'friend_id' => ['nullable', 'exists:App\Models\User,id'],
        ]);

        $user = auth()->user();

        if ($request->has('friend_id')) {
            $eventInvitation = $user->event_invitations()->create($data);
            return response()->json($eventInvitation, 201);
        }

        $friendships = $user->friendships;

        foreach ($friendships as $friendship) {
            $invitation = EventInvitation::query()
                ->where('event_id', $data['event_id'])
                ->where('friend_id', $friendship->friend_id)
                ->first();

            if (!$invitation) {
                $data = [
                    'event_id' => $data['event_id'],
                    'friend_id' => $friendship->friend_id,
                ];

                $user->event_invitations()->create($data);
            }
        }

        return response()->json(null, 201);
    }
}
