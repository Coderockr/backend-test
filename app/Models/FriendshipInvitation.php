<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendshipInvitation extends Model
{
    use Invitation;

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

}
