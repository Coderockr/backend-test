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
            'id' => $this->id,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'withdrawn_at' => $this->withdrawn_at,
            'is_withdrawn' => $this->is_withdrawn,
            'initial_investment' => $this->initial_investment,
            'person' => new PersonResource($this->whenLoaded('person')),
            'movements' => InvestmentMovementResource::collection($this->whenLoaded('movements')),
        ];
    }
}
