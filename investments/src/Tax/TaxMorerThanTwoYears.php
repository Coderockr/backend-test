<?php

namespace Investment\Tax;


class TaxMorerThanTwoYears
{
    protected $tax = 0.15;
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
        $initialDataTwoYearInFuture =$this->initialDate->copy()->addYear(2);

        if ($this->finalDate->gte($initialDataTwoYearInFuture)) {
            return $this->tax;
        }

        if ($this->next) {
            return $this->next->handle();
        }

    }
    
}