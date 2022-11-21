<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreInvestmentRequest extends FormRequest
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
            'person_id' => 'required',
            'description' => 'required',
            'gain' => 'required',
            'created_at' => 'required|date|date_format:Y-m-d H:i:s|before_or_equal:today',
            'initial_investment' => 'required|numeric|gt:0',
        ];
    }

    public function messages()
    {
        return [
            'person_id.required' => "Need to provide the owner of the investment",
            'description.required' => "Need to provide investment's description",
            'gain.required' => "Need to provide investment's gain amount",
            'created_at.required' => "Need to provide investment's created date",
            'created_at.date' => "The creation date must be a valid date",
            'created_at.date_format' => "The creation date must be in Y-m-d format",
            'created_at.before_or_equal' => "The creation date must be before or equal today",
            'initial_investment.required' => "Need to provide investment's initial amount",
            'initial_investment.numeric' => "The initial investment amount must be numeric value",
            'initial_investment.gt' => "The initial investment amount must be greater than 0",
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
