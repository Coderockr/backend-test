<?php

namespace App\Transformers;

use Illuminate\Database\Eloquent\Model;
use League\Fractal;
use Illuminate\Support\Facades\Log;

class InvestmentTransformer extends Fractal\TransformerAbstract
{
	public function transform(\App\Models\Investment $investment)
	{
	    return [
	        'investment_id' => $investment->id,
	        'already_withdrawed' => false
	    ];
	}
}