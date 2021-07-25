<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'biography', 'avatar', 'city', 'state'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'updated_at'];


    /**
     * A User can have multiple Events.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events() {
        return $this->hasMany(Event::class, 'owner_id', 'id');
    }


    /**
     * Generate the custom attribute to get user friends.
     *
     * @return static
     */
    public function getFriendsAttribute()
    {
        $friends = Friendship::where('user_id', $this->id)->orWhere('friend_id', $this->id)->get();
        $data = [];
        foreach ($friends as $f) {
            $data[] = $f->user_id == $this->id ? $f->friend : $f->user;
        }
        return collect($data)->unique();
    }

    /**
     * Generate the custom attribute to get user friends ids array.
     *
     * @return mixed
     */
    public function getFriendsIdsArrayAttribute()
    {
        return $this->friends->pluck('id')->toArray();
    }


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
