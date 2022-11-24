<?php

namespace App\Interfaces;

interface InvestmentRepositoryInterface 
{
    public function setInvestment(array $investment, int $ownerId);
    public function getAllInvestments();
    public function getInvestmentByOwner(int $ownerId);
}