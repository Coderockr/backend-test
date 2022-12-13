<?php

namespace App\Http\Requests\Investment;

use Illuminate\Foundation\Http\FormRequest;

class InvestmentCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'creation_date' => 'required|date|date_format:Y-m-d|before_or_equal:today',
            'amount' => 'required|numeric|gt:100',
            'email' => 'required|email|exists:owners',
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => 'Owner does not exist!',
        ];
    }
}
