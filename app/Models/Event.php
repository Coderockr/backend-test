<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['owner_id', 'name', 'description', 'date', 'time', 'city', 'state', 'status'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['updated_at', 'owner_id'];

    /**
     * Custom attributes for data model.
     *
     * @var array
     */
    public $appends = ['status_name'];


    /**
     * A Event belongs to a User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    /**
     * A Event has many invitations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invitations()
    {
        return $this->hasMany(EventInvitation::class, 'event_id', 'id');
    }

    /**
     * Return an array with the given status options
     *
     * @return array
     */
    public function getStatusArray() {
        return ['pending' => 'Pending', 'closed' => 'Closed', 'canceled' => 'Canceled'];
    }

    /**
     * Returns the status name for a custom attribute.
     *
     * @return string status_name
     */
    public function getStatusNameAttribute() {
        $status = $this->getStatusArray();
        return array_key_exists($this->status, $status) ? $status[$this->status] : '';
    }

    /**
     * Query scope to filter pending status
     *
     * @param $query
     * @return mixed
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Query scope to filter by the event owner
     *
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeOfOwner($query, $type) {
        return $query->where('owner_id', $type);
    }
}
