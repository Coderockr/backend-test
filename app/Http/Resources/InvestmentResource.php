<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'    =>  $this->id,
            'initial_value' => $this->initial_value,
            'expected_balance' => $this->expected_balance,
            'date' => $this->date,
            'withdraw' => $this->withdraw,
            'final_value' => $this->final_value,
        ];
    }
}
