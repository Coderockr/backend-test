<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestimentoResource extends JsonResource
{
    public $preserveKeys = true;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'investimento' => $this->investimento,
            'investidor' => $this->investidor->name,
            'valor_inicial' => $this->valor_inicial,
            'data_criacao' => \Carbon\Carbon::createFromFormat('Y-m-d', $this->data_criacao)->format('d/m/Y')
        ];
    }
}
