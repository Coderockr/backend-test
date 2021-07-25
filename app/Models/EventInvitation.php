<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventInvitation extends Model
{
    use Invitation;

    /**
     * Database table name
     *
     * @var string
    */
    protected $table = 'events_invitations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['event_id', 'user_id', 'guest_id', 'status'];

    /**
     * A EventInvitation belongs to a Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event() {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

}
