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
        return [1 => 'Pending', 2 => 'Closed', 3 => 'Canceled'];
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
}
