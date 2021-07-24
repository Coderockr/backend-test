<?php

namespace App\Http\Resources;

use App\Helpers\Hasher;

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
        return [
            'id' => $this->id,
            'owner' => [
                'id' => Hasher::encode($this->owner_id),
                'name' => $this->owner->name
            ],
            'name' => $this->name,
            'description' => $this->description,
            'date' => $this->date,
            'time' => $this->time,
            'city' => $this->city,
            'state' => $this->state,
            'status' => $this->status,
            'status_name' => $this->status_name,
            'created_at' => $this->created_at->toDateTimeString()
        ];
    }
}
