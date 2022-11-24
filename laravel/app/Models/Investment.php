<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Investment extends Model
{


    protected $fillable = ['owner_id', 'create_at', 'value_decimal'];

    protected $primaryKey = "owner_id";

    public $timestamps = false;

    protected $maps = [
        "owner_id" => "owner_id",
        "create_at" => "create_at",
        "value_decimal" => "value_decimal",
    ];

    /**
     * Get the Owner associated with the Investment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function owner() : HasOne
    {
        return $this->hasOne(Owner::class, 'owner_id', 'owner_id');
    }
}
