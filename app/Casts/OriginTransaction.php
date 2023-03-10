<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class OriginTransaction implements CastsAttributes
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function get($model, $key, $value, $attributes)
    {
        return \App\Service\Enums\OriginTransaction::from($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}
