<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterRequest extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    public $table = 'register_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'email'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['updated_at'];

    /**
     * A RegisterRequest belongs to a User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Query scope to filter by the request user
     *
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeOfUser($query, $type) {
        return $query->where('user_id', $type);
    }

    /**
     * Query scope to filter by the request email
     *
     * @param $query
     * @param $type
     * @return mixed
     */
    public function scopeOfEmail($query, $type) {
        return $query->where('email', $type);
    }
}
