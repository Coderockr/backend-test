<?php

namespace App\Domains\Person\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'public.people';

    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'person', 'name', 'nickname', 'photo', 'reason_social', 'cpf_cnpj', 'account', 'date_birth', 'gender', 'email', 'address_id',
        'name_dad', 'name_mother', 'note', 'password', 'role_id' 
    ];

    protected $casts = [
        'person' => 'boolean',
    ];

    public function role()
    {
        return $this->hasOne('App\Domains\Person\Models\Role', 'id', 'role_id');
    }

    public function address()
    {
        return $this->hasOne('App\Domains\System\Models\Address', 'id', 'address_id');
    }

    public function phone()
    {
        return $this->hasMany('App\Domains\Person\Models\Phone', 'person_id', 'id');
    }

    public function account()
    {
        return $this->hasMany('App\Domains\Person\Models\Account', 'person_id', 'id');
    }
}
