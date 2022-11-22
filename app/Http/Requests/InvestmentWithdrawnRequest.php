<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InvestmentWithdrawnRequest extends FormRequest
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
            "withdrawn_at" => 'required|date|date_format:Y-m-d H:i:s',
        ];
    }

    public function messages()
    {
        return [
            'withdrawn_at.required' => "Need to provide a withdrawn date to proceed",
            'withdrawn_at.date' => "The withdrawn date must be a valid date",
            'withdrawn_at.date_format' => "The withdrawn date must be in Y-m-d H:i:s format",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'message' => 'Invalid data send',
            'details' => $errors->messages(),
        ], 422);

        throw new HttpResponseException($response);
    }

    public function expectsJson()
    {
        return true;
    }
}
