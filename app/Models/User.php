<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'bio', 'location', 'picture_file', 'password',
    ];

    protected $hidden = [
        'picture_file', 'password', 'remember_token', 'email_verified_at',
    ];

    protected $appends = [
        'picture',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function friendship_requests()
    {
        return $this->hasMany(FriendshipRequest::class);
    }

    public function friendships()
    {
        return $this->hasMany(Friendship::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function getPictureAttribute()
    {
        return $this->picture_file ? Storage::url($this->picture_file) : null;
    }
}
