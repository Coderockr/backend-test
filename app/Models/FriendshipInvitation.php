<?php

namespace App\Models;

class FriendshipInvitation extends Invitation
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'friendship_invitations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'guest_id', 'status'];

    /**
     * Query scope to filter by the defined users
     *
     * @param $query
     * @param array $users
     * @return mixed
     */
    public function scopeOfUs($query, array $users) {
        $user_id = $users[0];
        $guest_id = $users[1];
        return $query->whereRaw("((user_id = $user_id AND guest_id = $guest_id) OR (user_id = $guest_id AND guest_id = $user_id))");
    }

}
