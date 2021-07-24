<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\EventCollection;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EventController extends ApiController
{
    /**
     * @var Event
     */
    private $eventModel;

    /**
     * EventController constructor.
     * @param Event $eventModel
     */
    public function __construct(Event $eventModel)
    {
        $this->eventModel = $eventModel;
    }

    /**
     * Display a public pending listing of the resource.
     *
     * @return EventCollection
     */
    public function publicEvents(Request $request)
    {
        $collection = $this->eventModel->pending();

        // Check query string filters.
        if ($state = $request->query('state')) {
            $collection = $collection->where('state', $state);
        }

        $collection = $collection->latest()->paginate(10);

        // Appends "status" to pagination links if present in the query.
        if ($state) {
            $collection = $collection->appends('state', $state);
        }

        return new EventCollection($collection); // ResourceCollection
        // return response()->json($collection);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
