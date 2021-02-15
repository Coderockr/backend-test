<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Scopes\UserScope;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $origin = $request->get('origin', 'mine');
        $user = auth()->user();

        if ($origin === 'invited') {
            return Event::query()
                ->withoutGlobalScope(UserScope::class)
                ->join('event_invitations', 'event_invitations.id', '=', 'events.id')
                ->where('event_invitations.friend_id', $user->id)
                ->select('events.*')
                ->get();
        }

        return $user->events;
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => ['required', 'max:60'],
            'description' => ['max:500'],
            'location' => ['required', 'max:100'],
            'moment' => ['date_format:Y-m-d H:i', 'after_or_equal:today'],
        ]);

        $user = auth()->user();
        $event = $user->events()->create($data);

        return response()->json($event, 201);
    }

    public function update(Request $request, Event $event)
    {
        $event->update($request->all());

        return response()->json($event);
    }

    public function delete(Event $event)
    {
        $event->delete();

        return response()->json(null, 204);
    }
}
