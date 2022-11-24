<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StorePersonRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'ssn' => 'required|unique:persons,ssn|regex:/^[0-9]+$/',
            'email' => 'required|unique:persons,email|email',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => "Need to provide person's first name",
            'last_name.required' => "Need to provide person's last name",
            'ssn.required' => "Need to provide person's Social Security Number",
            'ssn.unique' => "This email already exists",
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
