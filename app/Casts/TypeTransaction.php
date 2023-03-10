<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TypeTransaction implements CastsAttributes
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function get($model, $key, $value, $attributes)
    {
        return \App\Service\Enums\TypeTransaction::from($value);
    }

    public function set($model, $key, $value, $attributes)
    {
        return $value;
    }
}
