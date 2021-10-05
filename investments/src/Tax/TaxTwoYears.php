<?php

namespace Investment\Tax;


class TaxTwoYears
{
    protected $tax = 0.185;
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
        $initialDataTwoYearInFuture =$this->initialDate->copy()->addYears(2)->subDay();

        if ($this->finalDate->between($this->initialDate, $initialDataTwoYearInFuture)) {
            return $this->tax;
        }

        if ($this->next) {
            return $this->next->handle();
        }

    }
}