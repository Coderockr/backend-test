<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name'                    => "required|min:3|max:40",
            'email'                   => ['required', 'email','min:3','max:100',Rule::unique('users', 'email')->ignore($this->id)],
            'password'                => "required|confirmed|min:6|max:10",
        ];

        if($this->method() === 'PUT'){
            $rules['id'] = 'required';
        }

        return $rules;
    }

}
