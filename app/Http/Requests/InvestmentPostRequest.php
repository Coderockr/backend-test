<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class InvestmentPostRequest extends FormRequest
{
    public function rules()
    {
        return [
            'person_id' => 'required',
            'initial_value' => 'required|numeric|gt:0',
            'date' => 'required|date_format:Y-m-d|before_or_equal:now',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }

    public function messages()
    {
        return [
            'person_id.required' => 'The field person_id is required',
            'initial_value.required' => 'The field initial_value is required',
            'initial_value.numeric' => 'The field initial_value must be numeric',
            'initial_value.gt' => 'The field initial_value must be greater than 0',
            'date.required' => 'The field date is required',
            'date.date_format' => 'The format of date is wrong, must be Y-m-d',
        ];
    }
}
