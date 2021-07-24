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
            'created_at' => (string)$this->created_at->toDateTimeString(),
            'id' => $this->id,
            'owner' => Hasher::encode($this->owner_id),
            'name' => $this->name,
            'description' => $this->description,
            'date' => $this->date,
            'time' => $this->time,
            'city' => $this->city,
            'state' => $this->state,
            'status' => $this->status,
            'status_name' => $this->status_name,
        ];
    }
}
