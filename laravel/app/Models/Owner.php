<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Owner extends Model
{


    protected $fillable = ['id', 'owner_name'];


    protected $maps = [
        "id" => "owner_id",
        "owner_name" => "owner_name"
    ];

    /**
     * Get the Owner associated with the Investment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function investments() : HasMany
    {
        return $this->hasMany(Investment::class, 'owner_id', 'id');
    }
}
