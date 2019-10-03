<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingCompanyDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'cpf'          => $this->cpf,
            'ie'           => $this->ie,
            'address'      => $this->address,
            'cep'          => $this->cep,
            'number'       => $this->number,
            'neighborhood' => $this->neighborhood,
            'cnpj'         => $this->cnpj,
            'fantasy'      => $this->fantasy,
            'complement'   => $this->complement,
            'state_id'     => $this->state_id,
            'city_id'      => $this->city_id,
        ];
    }
}
