<?php

namespace App\Http\Resources;

use App\Models\EventInvitation;

class EventResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Get the confirmed users for the event
        $confirmed_users = $this->invitations->where('status', 'confirmed')->transform(function(EventInvitation $invitation) {
            return (new SimpleUserResource($invitation->guest));
        });

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'date' => $this->date,
            'time' => $this->time,
            'city' => $this->city,
            'state' => $this->state,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'created_at' => $this->created_at->toDateTimeString(),
            'owner' => [
                'id' => $this->owner_id,
                'name' => $this->owner->name
            ],
            'confirmed_users' => $confirmed_users ?: []
        ];
    }
}
