<?php

namespace App\Http\Resources;

class FriendshipInvitationResource extends ApiResource
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
            'user' => [
                'id' => $this->user_id,
                'name' => $this->user->name
            ],
            'status' => $this->status,
            'status_name' => $this->status_name,
            'created_at' => $this->created_at->toDateTimeString()
        ];
    }
}
