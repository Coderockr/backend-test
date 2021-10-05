<?php

namespace Investment\Tax;

use Carbon\Carbon;


class TaxOneYear
{
    protected $tax = 0.225;
    protected $next = null;


    public function __construct($initialDate, $finalDate) {
        $this->initialDate = $initialDate;
        $this->finalDate = $finalDate;
    }

    public function setNext($next)
    {
        $this->next = $next;
    }

    public function handle()
    {
        $initialDataOneYearInFuture =$this->initialDate->copy()->addYear()->subDay();

        if ($this->finalDate->between($this->initialDate, $initialDataOneYearInFuture)) {
            return $this->tax;
        }

        if ($this->next) {
            return $this->next->handle();
        }

    }
}
