<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Money\Currency;

class Money implements CastsAttributes
{
    protected $amount;
    protected $currency;

    public function __construct($amount, $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return new \Money\Money(
            $attributes[$this->amount],
            new Currency($attributes[$this->currency])
        );
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return [
            $this->amount => (int) $value->getAmount(),
            $this->currency => (string) $value->getCurrency()
        ];
    }
}
