<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Model;
use League\Fractal;
use Illuminate\Support\Facades\Log;

class InvestmentTransformer extends Fractal\TransformerAbstract
{
	protected $decimals;
	protected $dateFormat;
	protected $showId;
	protected $showWithdrawDate;

	public function __construct($decimals, $dateFormat, $showId = true, $showWithdrawDate = true) {
		$this->decimals = $decimals;
		$this->dateFormat = $dateFormat;
		$this->showId = $showId;
		$this->showWithdrawDate = $showWithdrawDate;
	}

	public function transform(\App\Models\Investment $investment)
	{
		

		$data = [
	        'already_withdrawed' => $investment->withdraw_timestamp != null,
			'creation_date' => \App\Services\DateTimeNodeTransformer::fromDateTimeStringToNode($investment->investment_timestamp, $this->dateFormat),
			'initial_amount' => \App\Services\FinancialValueNodeTransformer::fromDatabaseIntToNode($investment->value, $this->decimals),
			'amount' => \App\Services\FinancialValueNodeTransformer::fromDatabaseIntToNode(
				\App\Services\InvestmentProcessor::proccessInvestment(
					$investment->value,
					\App\Services\DateTimeNodeTransformer::fromDateTimeStringToDateTime($investment->investment_timestamp),
					\App\Services\DateTimeNodeTransformer::fromDateTimeStringToDateTime($investment->withdraw_timestamp)
				),
				$this->decimals
			)
	    ];

		//$intValue, $initialData, $withdrawData

		//
		$data = $this->showId ? array_merge(['investment_id' => $investment->id], $data) : $data;
		$data = $this->showWithdrawDate ? array_merge(['withdraw_date' => \App\Services\DateTimeNodeTransformer::fromDateTimeStringToNode($investment->withdraw_timestamp, $this->dateFormat)], $data) : $data;
	    return $data;
	}
}