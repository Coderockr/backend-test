<?php

namespace App\Http\Resources;

class EventInvitationResource extends ApiResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'created_at' => $this->created_at->toDateTimeString(),
            'event' => [
                'id' => $this->event->id,
                'name' => $this->event->name,
                'date' => $this->event->date,
                'owner' => [
                    'id' => $this->event->owner_id,
                    'name' => $this->event->owner->name,
                ]
            ]
        ];
    }
}
