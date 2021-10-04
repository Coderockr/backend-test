<?php

namespace Investment\Tax;



class TaxLessthanYear
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
        if ($this->finalDate->year == $this->initialDate->year) {
            return $this->tax;
        }

        if ($this->next) {
            return $this->next->handle();
        }

    }
}
