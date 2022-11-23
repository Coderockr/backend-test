<?php

namespace App\Service;

use App\Entity\Investment;
use DateTimeImmutable;

class InvestmentCalculator
{
	public function calculateGains(Investment $investment, ?DateTimeImmutable $date = null): float
	{
		$balance = $investment->value();

		$dateDiff = $date ?? new DateTimeImmutable('now');
		$dateInterval = $investment->createdAt()->diff($dateDiff);

		for ($i = $dateInterval->m; $i  > 0 ; $i--) { 
			$gains = ($balance * 0.52) / 100;
			$balance += $gains;
		}

		return $balance - $investment->value();
	}
}
