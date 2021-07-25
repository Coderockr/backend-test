<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['updated_at', 'user_id', 'guest_id'];

    /**
     * Custom attributes for data model.
     *
     * @var array
     */
    public $appends = ['status_name'];


    /**
     * A Invitation belongs to a User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * A Invitation belongs to a Guest (User).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guest()
    {
        return $this->belongsTo(User::class, 'guest_id', 'id');
    }

    /**
     * Return an array with the given status options
     *
     * @return array
     */
    public function getStatusArray()
    {
        return ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'rejected' => 'Rejected'];
    }

    /**
     * Returns the status name for a custom attribute.
     *
     * @return string status_name
     */
    public function getStatusNameAttribute()
    {
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
     * Query scope to filter rejected status
     *
     * @param $query
     * @return mixed
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Query scope to filter confirmed status
     *
     * @param $query
     * @return mixed
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Query scope to filter by the guest
     *
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeOfGuest($query, $type) {
        return $query->where('guest_id', $type);
    }
}