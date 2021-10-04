<?php

namespace Investment\Tax;


class TaxTwoYears
{
    protected $tax = 0.185;
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
        if ($this->finalDate->year == $this->initialDate->year) {
            return $this->tax;
        }

        if ($this->next) {
            return $this->next->handle();
        }

    }
}