<?php

namespace App\Http\Requests;

use App\Models\Investment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvestmentWithdrawalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $investment = Investment::findOrFail($this->id);

        $rules = [
            'id'                            => "required|exists:investments,id",
            'withdrawal_date'               => ['required','date','date_format:Y-m-d','before_or_equal:now','after_or_equal:'.$investment->investment_date],
        ];

        return $rules;
    }

}
