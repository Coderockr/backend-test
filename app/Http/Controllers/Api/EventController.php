<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\EventResource;
use Exception;
use App\Http\Resources\EventCollection;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
     * Display a pending list of the resource of the user.
     *
     * @return EventCollection
     */
    public function index(Request $request)
    {
        $user = auth('api')->user();

        $collection = $this->eventModel->ofOwner($user->id)
                                       ->pending()
                                       ->orderBy('date')
                                       ->orderBy('time')
                                       ->latest()
                                       ->paginate(10);

        return new EventCollection($collection);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = $this->formValidate($data = $request->all());

        if ($validator->fails()) {
            return $this->responseUnprocessable($validator->errors()->toArray());
        }

        try {
            $event = $this->create($data);
            return $this->responseResourceCreated('Resource created.');
        } catch (Exception $e) {
            return $this->responseServerError('Error creating resource.');
        }
    }

    /**
     * Create a new event instance after a valid registration.
     *
     * @param $data
     * @return mixed
     */
    protected function create($data)
    {
        // Set the owner id
        $data['owner_id'] = auth('api')->user()->id;
        // Default status
        $data['status'] = 'pending';

        return $this->eventModel->create($data);
    }

    /**
     * Input form validation rules
     *
     * @param $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function formValidate($data) {
        return Validator::make($data, [
            'name' => 'required|string|min:5|max:255',
            'description' => 'required|string',
            'date' => 'required|date_format:Y-m-d|after_or_equal:' . date('Y-m-d'),
            'time' => 'required|date_format:H:i',
            'city' => 'required|string|min:2|max:175',
            'state' => 'required|string|min:2|max:2',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return EventResource|\Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $event = $this->eventModel->findOrFail($id);

        // User can only edit their own pending events.
        if ($event->owner_id != auth('api')->user()->id || $event->status != 'pending') {
            return $this->responseUnauthorized();
        }

        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $this->formValidate($data = $request->all());

        if ($validator->fails()) {
            return $this->responseUnprocessable($validator->errors()->toArray());
        }

        try {
            $event = $this->eventModel->findOrFail($id);

            // User can only acccess their own data.
            if ($event->owner_id == auth('api')->user()->id) {
                unset($data['status'], $data['owner_id']);

                $event->update($data);

                return $this->responseResourceUpdated();
            } else {
                return $this->responseUnauthorized();
            }
        } catch (Exception $e) {
            return $this->responseServerError('Error updating resource.');
        }
    }

    /**
     * Sets the status of the specified resource to canceled
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {
        $event = $this->eventModel->findOrFail($id);

        // User can only cancel their own pending events.
        if ($event->owner_id != auth('api')->user()->id || $event->status != 'pending') {
            return $this->responseUnauthorized();
        }

        try {
            $event->update(['status' => 'canceled']);
            // TODO: Criar evento para cancelar os convites para os amigos participarem
            return $this->responseResourceUpdated('Event canceled.');
        } catch (Exception $e) {
            return $this->responseServerError('Error deleting resource.');
        }
    }
}
