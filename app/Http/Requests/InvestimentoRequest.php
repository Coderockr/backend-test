<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvestimentoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'investimento' => ['required', 'string', Rule::in(['CDB', 'TESOURO_PRE', 'TESOURO_POS'])],
            'investidor_id' => 'required|integer|gt:0',
            'valor_inicial' => 'required|decimal:1,2|gt:0',
            'data_criacao' => 'required|date_format:Y-m-d'
        ];
    }
}
