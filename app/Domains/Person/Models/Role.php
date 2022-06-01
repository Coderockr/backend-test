<?php

namespace App\Domains\Person\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $table = 'public.roles';

    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'active', 'name'
    ];

    public function groupRole()
    {
        return $this->belongsToMany('App\Domains\Person\Models\GroupRole', 'grouped_roles', 'role_id', 'group_role_id');
    }

}
