<?php

namespace App\Http\Resources;

use App\Models\EventInvitation;

class EventInvitationCollection extends ApiResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Transforms the collection to match format in FriendshipInvitationResource.
        $this->collection->transform(function(EventInvitation $item) {
            return (new EventInvitationResource($item));
        });

        return parent::toArray($request);
    }
}
