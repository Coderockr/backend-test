<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return Event::paginate(10);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => ['required', 'max:60'],
            'description' => ['max:500'],
            'location' => ['required', 'max:100'],
            'moment' => ['date_format:Y-m-d H:i:s', 'after_or_equal:today'],
        ]);

        $event = Event::create($data);

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
