<?php

namespace App\Models;

class EventInvitation extends Invitation
{
    /**
     * Database table name
     *
     * @var string
    */
    public $table = 'events_invitations';

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
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    /**
     * Query scope to filter by the event
     *
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeOfEvent($query, $type) {
        return $query->where('event_id', $type);
    }
}
