<?php

namespace App\Models;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'event_id', 'friend_id',
    ];

    protected $with = [
        'event', 'user', 'friend',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UserScope());
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
