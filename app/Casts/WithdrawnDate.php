<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Carbon\Carbon;

class WithdrawnDate implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, $key, $value, $attributes)
    {
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {
        $now = Carbon::now()->toDateTimeString();
        $parseDate = Carbon::parse($value);
        $isFutureValue = $parseDate->isAfter($now);
        $isBeforeCreatedAt = $parseDate->isBefore($attributes['created_at']);
        
        return ($isBeforeCreatedAt || $isFutureValue) ? $now : $value;
    }
}