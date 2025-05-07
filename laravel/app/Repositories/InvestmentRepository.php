<?php

Namespace App\Repositories;

use App\Http\Resources\InvestmentResource;
use App\Models\Investment;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Date;
use PhpParser\Node\Expr\Cast\Double;

use function PHPUnit\Framework\throwException;

class InvestmentRepository
{
    
    public function getInvestmentByOwner(int $ownerId)
    {
        return Investment::where('owner_id', '=', $ownerId)->paginate(3);
    }

    public function setInvestment(array $investment, int $ownerId)
    {
        $investmentNew = new Investment();
        $investmentNew->owner_id = $ownerId;
        
        $investmentNew->create_at = $investment["create_at"];

        $investmentNew->value_decimal = $investment["value_decimal"];
        return $investmentNew->save();
    }
    
    public function getAllInvestments()
    {
        return Investment::all();
    }
    
    public function getInvestmentById(int $investmentId)
    {
        return Investment::where("id", "=", $investmentId)->get()->first();
    }

    public function getValueWithGain(Investment $investment)
    {
        $gain = self::setGain($investment, floatval(self::calculateGain($investment->create_at, $investment->value_decimal)));
        return [
            "initial_amount" => floatval($investment->value_decimal),
            "expected_balance" => $gain
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

    public function setGain(Investment $investment, float $gain)
    {
        $investment->value_final = $gain;
        $investment->save();
        return $gain;
    }

    public function withdrawalInvestment(Investment $investment, string $date)
    {
        if ($date > $investment->create_at && $investment->value_final > 0) {
            $taxes = self::getValueWitoutTaxes($investment);

            $value = $investment->value_final - $taxes;
            
            $investment->value_final = 0.00;
            $investment->value_decimal = 0.00;
            $investment->save();

            return [
                "withdrawalValue" => floatval(number_format($value, 2, '.', '')), 
                "taxes" => floatval(number_format($taxes, 2, '.', ''))
            ];
        } else if ($investment->value_final == 0 && $investment->value_decimal == 0) {
            return throw new Exception("O investmento desejado já não possui mais saldo para retirada.");
        }
        return throw new Exception("A data de retirada informada é menor que a data do investmento.");
    }


    public function getValueWitoutTaxes(Investment $investment)
    {
        $toDate = Carbon::parse($investment->create_at);
        $fromDate = Carbon::parse(now());
        $investmentAge = $toDate->diffInMonths($fromDate);
        
        if ($investmentAge <= 12) {
            $taxes = 0.225;
        }elseif ($investmentAge > 12 && $investment <= 24) {
            $taxes = 0.185;
        }else {
            $taxes = 0.15;
        }
        return ($investment->value_final * $taxes);
    }
}