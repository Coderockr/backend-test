<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Investment extends JsonResource
{
    /**
     * Transform the resource into an array to list investments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($investments)
    {
        return [
            'id' => $this->id,
            'owner' => $this->owner,
            'amount' => $this->amount,
            'create date' => $this->create_date,
        ];
    }

    /**
     * Transform the resource into an array to list investment by id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArrayInvest($investments)
    {
        return [
            'id' => $this->id,
            'owner' => $this->owner,
            'amount' => $this->amount,
            'expected balance' => $this->expected_balance,
            'create date' => $this->create_date,
        ];
    }

    /**
     * Transform the resource into an array to list errors when investment not found.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArrayNotFound($e)
    {
        return [
            'Error' => 'Investment not found',
            'Exception' => $e->getMessage()
        ];
    }
}
