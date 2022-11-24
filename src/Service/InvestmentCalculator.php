<?php

namespace App\Service;

use App\Entity\Investment;
use DateTimeImmutable;

class InvestmentCalculator
{
	public function calculateTaxes(Investment $investment, ?DateTimeImmutable $dateOfWithdrawal = null): float
	{
		$date = $dateOfWithdrawal ?? new DateTimeImmutable('now');
		$gains = $this->calculateGains($investment, $date);
		$dateDiff = $investment->createdAt()->diff($date);

		if ($dateDiff->y < 1) {
			return ($gains * 22.5) / 100;
		}

		if ($dateDiff->y < 2) {
			return ($gains * 18.5) / 100;
		}

		return ($gains * 15) / 100;
	}

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
