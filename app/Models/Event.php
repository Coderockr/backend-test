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
    protected $hidden = ['updated_at'];

    /**
     * A Event belongs to a User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    /**
     * Custom attributes for data model.
     *
     * @var array
     */
    public $appends = ['status_name'];

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


    // Query Scope
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Global Scope
    public function scopeOfOwner($query, $type){
        return $query->where('owner_id', $type);
    }
}
