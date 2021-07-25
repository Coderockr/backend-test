<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'friendships';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'friend_id'];

    /**
     * A Invitation belongs to a User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * A Invitation belongs to a Guest (User).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function friend() {
        return $this->belongsTo(User::class, 'friend_id', 'id');
    }
}
