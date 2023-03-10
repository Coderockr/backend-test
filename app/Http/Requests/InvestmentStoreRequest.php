<?php

namespace App\Http\Requests;

class InvestmentStoreRequest extends BaseRequestValidator
{
    /**
     * @return string[]
     */
    protected function validationRules(): array
    {
        return [
             'name' => 'string|min:3|max:100',
             'balance' => 'required|integer|gte:0',
             'create_date' => 'required|timestamp'
         ];
    }
}
