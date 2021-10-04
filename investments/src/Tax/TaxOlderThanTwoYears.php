<?php

namespace Investment\Tax;


class TaxOlderThanTwoYears
{
    protected $tax = 0.15;
    protected $next = null;

    public function __construct($initialDate, $finalDate) {
        $this->initialDate = $investment;
        $this->finalDate = $finalDate;
    }

    public function setNext($next)
    {
        $this->next = $next;
    }

    public function handle()
    {
        $diff = $this->finalDate->year - $this->initialDate->year;

        if ($diff > 2) {
            return $this->tax;
        }

        if ($this->next) {
            return $this->next->handle();
        }

    }
    
}