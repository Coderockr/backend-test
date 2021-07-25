<?php

namespace App\Http\Resources;

use App\Models\User;

class FriendshipCollection extends ApiResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Transforms the collection to match format in FriendshipResource.
        $this->collection->transform(function(User $user) {
            return (new SimpleUserResource($user));
        });

        return parent::toArray($request);
    }
}
