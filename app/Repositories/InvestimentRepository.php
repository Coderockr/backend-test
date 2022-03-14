<?php

namespace App\Repositories;

use App\Models\Investiment;
use DateTime;

use function Psy\debug;

class InvestimentRepository
{
    protected $entity;

    public function __construct(Investiment $model)
    {
        $this->entity = $model;
    }

    public function getAllInvestiments()
    {
        return $this->entity->paginate();
    }

    public function createNewInvestment(array $data): Investiment
    {
        return $this->entity->create($data);
    }

    public function getInvestiment(string $identify)
    {
        return $this->entity->findOrFail($identify);
    }

    public function update(array $data, int $id)
    {
        $investiment = $this->entity->findOrFail($id);
        $data['id'] = $investiment->id;

        return $investiment->update($data);
    }

    public function getCurrentValue(float $value, string $date)
    {
        $investimentTime = self::getInvestimentTime($date);

        return (float) self::getIncome($value, $investimentTime);
    }

    public static function getInvestimentTime(string $date)
    {
        $dateInvestiment = new DateTime($date);
        $dayInvestiment = (int) $dateInvestiment->format('d');

        $dateNow = new DateTime(date("Y-m-d"));
        $today = (int) $dateNow->format('d');
        $diffTime = $dateInvestiment->diff($dateNow);
        $daysToMonth = self::daysToMonth($diffTime->days);

        if ($today >= $dayInvestiment) {
            return $daysToMonth;
        }
        $time = $daysToMonth - 1;

        if ($time < 0) {
            $time = 0;
        }
        return $time;
    }

    public static function daysToMonth(int $days)
    {
        if ($days < 30) {
            return 0;
        }
        $months = (int) ($days / 30);
        return $months;
    }

    public static function getIncome(float $value, int $investimentTime)
    {
        $finalValue = $value;

        if ($investimentTime > 0) {
            $i = 0;

            while ($i < $investimentTime) {
                $value = number_format(($value * 0.0052 + $value), 2, '.', '');
                $finalValue = $value;
                $i++;
            }
            return $finalValue;
        }
        return number_format($finalValue, 2, '.', '');
    }
}
