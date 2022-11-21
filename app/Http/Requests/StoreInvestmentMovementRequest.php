<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreInvestmentMovementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function messages()
    {
        return [
            'person_id.required' => "Need to provide the owner of the investment",
            'description.required' => "Need to provide investment's description",
            'gain.required' => "Need to provide investment's gain amount",
            'created_at.required' => "Need to provide investment's created date",
            'ssn.regex' => "The person's Social Security Number must contain only numerical value",
            'email.required' => "Need to provide person's e-mail",
            'email.unique' => "This email already exists",
            'email.email' => "The e-mail entered must be a valid e-mail",
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
