<?php

namespace App\Tests\Service;

use App\Entity\Investment;
use App\Entity\User;
use App\Service\InvestmentCalculator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class InvestmentCalculatorTest extends TestCase
{
    public function testCalculateGains(): void
	{
		$user = new User();
		$investment = new Investment($user, 100.0, (new DateTimeImmutable('2022-03-12')));
		$dateCompare = new DateTimeImmutable('2022-04-12');

		$investmentCalculator = new InvestmentCalculator();
		$result = round($investmentCalculator->calculateGains($investment, $dateCompare), 2);

		$this->assertEquals($result, 0.52);
    }

	public function testCalculateTaxes(): void
	{
		$user = new User();
		$investment = new Investment($user, 100.0, (new DateTimeImmutable('2022-03-12')));
		$dateCompare = new DateTimeImmutable('2022-04-12');

		$investmentCalculator = new InvestmentCalculator();
		$result = round($investmentCalculator->calculateTaxes($investment, $dateCompare), 2);

		$this->assertEquals($result, 0.12);

		$investment = new Investment($user, 100.0, (new DateTimeImmutable('2021-03-12')));
		$dateCompare = new DateTimeImmutable('2022-04-12');

		$investmentCalculator = new InvestmentCalculator();
		$result = round($investmentCalculator->calculateTaxes($investment, $dateCompare), 2);

		$this->assertEquals($result, 0.1);

		$investment = new Investment($user, 100.0, (new DateTimeImmutable('2020-03-12')));
		$dateCompare = new DateTimeImmutable('2022-04-12');

		$investmentCalculator = new InvestmentCalculator();
		$result = round($investmentCalculator->calculateTaxes($investment, $dateCompare), 2);

		$this->assertEquals($result, 0.08);
	}
}
