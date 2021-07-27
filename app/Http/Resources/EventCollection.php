<?php

namespace App\Http\Resources;

use App\Models\Event;
use App\Http\Resources\ApiResourceCollection;
use App\Http\Resources\EventResource;

class EventCollection extends ApiResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Transforms the collection to match format in EventResource.
        $this->collection->transform(function(Event $event) {
            return (new EventResource($event));
        });

        return parent::toArray($request);
    }
}
