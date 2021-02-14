<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Scopes\UserScope;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();
        $query->withoutGlobalScope(UserScope::class);

        if ($date = request()->get('date')) {
            $query->whereDate('moment', $date);

            if ($time = request()->get('time')) {
                $query->whereTime('moment', $time . ':00');
            }
        }

        if ($location = request()->get('location')) {
            $query->where('location', 'LIKE', "%{$location}%");
        }

        return $query->paginate(10);
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

    public function delete(Request $request, Event $event)
    {
        $event->delete();

        return response()->json(null, 204);
    }
}
