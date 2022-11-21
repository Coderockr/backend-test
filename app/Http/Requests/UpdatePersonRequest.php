<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdatePersonRequest extends FormRequest
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
            'first_name' => 'nullable',
            'last_name' => 'nullable',
            'ssn' => 'nullable|regex:/^[0-9]+$/|unique:persons,ssn,'.request()->route('person_id'),
            'email' => 'nullable|email|unique:persons,email,'.request()->route('person_id'),
        ];
    }

    public function messages()
    {
        return [
            'ssn.unique' => "This email already exists",
            'ssn.regex' => "The person's Social Security Number must contain only numerical value",
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
