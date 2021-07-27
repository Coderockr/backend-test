<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class PublicEventController extends ApiController
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
    public function index(Request $request)
    {
        $collection = $this->eventModel->pending();

        // Check state query string filter.
        if ($city = $request->query('city')) {
            $collection = $collection->where('city', $city);
        }

        // Check state query string filter.
        if ($state = $request->query('state')) {
            $collection = $collection->where('state', $state);
        }

        // Check date query string filter.
        if ($date = $request->query('date')) {
            $date = dateDBFormat($date);
            $collection = $collection->where('date', $date);
        }

        $collection = $collection->orderBy('date')->orderBy('time')->latest()->paginate(10);

        // Appends "city" to pagination links if present in the query.
        if ($city) {
            $collection = $collection->appends('city', $city);
        }

        // Appends "state" to pagination links if present in the query.
        if ($state) {
            $collection = $collection->appends('state', $state);
        }

        // Appends "date" to pagination links if present in the query.
        if ($date) {
            $collection = $collection->appends('date', $date);
        }

        return new EventCollection($collection); // ResourceCollection
    }


    /**
     * Display the specified resource.
     *
     * @param  int $event_id
     * @return EventResource|\Illuminate\Http\JsonResponse
     */
    public function show($event_id)
    {
        try {
            $event = $this->eventModel::findOrFail($event_id);
            return new EventResource($event);
        } catch (Exception $e) {
            return $this->responseServerError('Event not found.');
        }
    }

}
