<?php

Namespace App\Repositories;

use App\Http\Resources\InvestmentResource;
use App\Models\Investment;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Date;
use PhpParser\Node\Expr\Cast\Double;

class InvestmentRepository
{
    
    public function setInvestment(array $investment, int $ownerId)
    {
        $investmentNew = new Investment();
        $investmentNew->owner_id = $ownerId;
        
        $investmentNew->create_at = "2023-05-10";

        $investmentNew->value_decimal = $investment["value_decimal"];
        return $investmentNew->save();
    }
    
    public function getAllInvestments()
    {
        return Investment::all();
    }
    
    public function getInvestmentByOwner(int $ownerId)
    {
        return Investment::where("owner_id", "=", $ownerId)->get()->first();
    }

    public function getValueWithGain(Investment $investment)
    {
        return [
            "initial_amount" => floatval($investment->value_decimal),
            "expected_balance" => floatval(self::calculateGain($investment->create_at, $investment->value_decimal))
        ];
    }


    public function calculateGain(string $investmentDate, string $investmentValue)
    {
        $toDate = Carbon::parse($investmentDate);
        $fromDate = Carbon::parse(now());
        $countDiffMonth = $toDate->diffInMonths($fromDate);
        
        if ($countDiffMonth > 0) {
            return self::multiGain($countDiffMonth - 1, $investmentValue);
        }
        return number_format($investmentValue, 2, '.', '');
    }

    public function multiGain($qtdMes, $value1)
    {
        $novoValor = ($value1 * 0.0052) + $value1;
        if ($qtdMes > 0) {
            return self::multiGain($qtdMes - 1, $novoValor);
        }
        return number_format($novoValor, 2, '.', '');
    }
}