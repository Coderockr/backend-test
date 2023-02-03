<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class InvestimentoStoreRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'investidor_id' => ['string', 'required', 'exists:investidors,id'],
            'data' => ['date_format:Y-m-d', 'required', 'before:tomorrow'],
            'saldo_inicial' => ['integer', 'required', 'min:0']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Erros de validação',
            'errors'      => $validator->errors()
        ], 422));
    }
}