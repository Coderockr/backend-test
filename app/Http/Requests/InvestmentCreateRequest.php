<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvestmentCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'user_id'                     => "required|exists:users,id",
            'investment_amount'           => "required|numeric|min:0|not_in:0",
            'investment_date'             => "required|date|date_format:Y-m-d|before_or_equal:now",
            //'withdrawal_date'               => "nullable|date|date_format:Y-m-d|before_or_equal:now|after:investment_date",
            //'withdrawal_amount'             => "nullable|numeric|min:0|not_in:0",
        ];

        return $rules;
    }

}
