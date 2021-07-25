<?php

namespace App\Http\Resources;

use App\Models\FriendshipInvitation;

class FriendshipInvitationCollection extends ApiResourceCollection
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
        $this->collection->transform(function(FriendshipInvitation $event) {
            return (new FriendshipInvitationResource($event));
        });

        return parent::toArray($request);
    }
}
