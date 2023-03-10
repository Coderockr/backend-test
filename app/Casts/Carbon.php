<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Carbon implements CastsAttributes
{
    protected $create_date;

    public function __construct($date)
    {
        $this->create_date = $date;
    }

    public function get($model, $key, $value, $attributes)
    {
        return new \Carbon\Carbon(new \DateTime($attributes[$this->create_date]));
    }

    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}
