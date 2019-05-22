<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'title'              => $this->title,
            'cnpj'               => $this->cnpj,
            'address'            => $this->address,
            'phone'              => $this->phone,
            'state_id'           => $this->state_id,
            'city_id'            => $this->city_id,
            'neighborhood'       => $this->neighborhood,
            'number'             => $this->number,
            'complement'         => $this->complement,
            'state_registration' => $this->state_registration,
            'cep'                => $this->cep,
            'company'            => new CompanyResource($this->company()->first())
        ];
    }
}
