<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Resources\EventResource;
use App\Models\EventInvitation;
use App\Http\Resources\EventCollection;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
     * @param Request $request
     * @return EventCollection|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Validate the confirmed users query param
        $validator = Validator::make($request->all(), [
            'users' => 'array',
            'users.*' => 'integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return $this->responseUnprocessable($validator->errors()->toArray());
        }

        // Current user
        $user = auth('api')->user();

        $collection = $this->eventModel->select($this->eventModel->table . '.*')->ofOwner($user->id)->pending();

        // Check confirmed users query string filter.
        if ($users = $request->get('users')) {
            $collection = $collection->leftJoin('events_invitations', 'events_invitations.event_id', '=', 'events.id')
                                     ->groupBy('events.id')
                                     ->whereIn('guest_id', $users)
                                     ->where('events_invitations.status', 'confirmed');
        }

        $collection = $collection->orderBy('date')->orderBy('time')->latest()->paginate(10);

        // Appends "users" to pagination links if present in the query.
        if ($users) {
            $collection = $collection->appends('users', $users);
        }

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
            return $this->responseResourceCreated('Resource created.', $event->id);
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
            // Se the event has canceled
            $event->update(['status' => 'canceled']);

            // Remove the invitations sent to the users
            EventInvitation::where('event_id', $id)->delete();

            return $this->responseResourceUpdated('Event canceled.');
        } catch (Exception $e) {
            return $this->responseServerError('Error canceling resource.');
        }
    }


    /**
     * Call to the invite method handles to the all user friends
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function inviteAllFriends(Request $request, $id)
    {
        return $this->invite($request, $id, 'all');
    }

    /**
     * Call to the invite method handles to the selected user friends
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function inviteSelectedFriends(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return $this->responseUnprocessable($validator->errors()->toArray());
        }

        return $this->invite($request, $id, 'selected');
    }

    /**
     * @param Request $request
     * @param $id
     * @param $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function invite(Request $request, $id, $action)
    {
        $event = $this->eventModel->findOrFail($id);
        $me = auth('api')->user();

        // User can only cancel their own pending events.
        if ($event->owner_id != $me->id || $event->status != 'pending') {
            return $this->responseUnauthorized();
        }

        // Get all user friends ids
        $my_friends = $me->friendsIdsArray;

        $friends_to_invite = [];
        if ($action == 'selected') {
            // If the invitation for the selected friends, get he's ids from the form
            foreach ($request->get('ids', []) as $friend_id) {
                if (in_array($friend_id, $my_friends)) {
                    $friends_to_invite[] = (int) $friend_id;
                }
            }
        } else {
            // All friends invitation
            $friends_to_invite = $my_friends;
        }

        // Check if have any friend to invite
        if (!$friends_to_invite) {
            return $this->responseUnprocessable(['No friends to invite']);
        }

        // Check if any friends have already been invited to not send again
        $friends_already_invited = EventInvitation::ofEvent($id)
                                                  ->whereIn("guest_id", $friends_to_invite)
                                                  ->get()
                                                  ->pluck('guest_id')
                                                  ->toArray();
        if ($friends_already_invited) {
            $friends_to_invite = array_diff($friends_to_invite, $friends_already_invited);
        }

        // Check if have any friend to invite
        if (!$friends_to_invite) {
            return $this->responseUnauthorized(['No friends to invite']);
        }

        // Generate the invitation data to store
        $invites = [];
        $now = now();
        foreach ($friends_to_invite as $friend_id) {
            $invites[] = [
                'event_id' => $id,
                'user_id' => $me->id,
                'guest_id' => $friend_id,
                'status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        try {
            // Store the invitations
            EventInvitation::insert($invites);

            return $this->responseResourceUpdated('Friends invited.');
        } catch (Exception $e) {
            return $this->responseServerError('Error inviting friends.');
        }
    }
}
