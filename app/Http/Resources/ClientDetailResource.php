<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientDetailResource extends JsonResource
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
            'id'                 => $this->id,
            'title'              => $this->title,
            'cnpj'               => $this->cnpj,
            'cpf'                => $this->cpf,
            'fantasy'            => $this->fantasy,
            'address'            => $this->address,
            'phone'              => $this->phone,
            'state_id'           => $this->state_id,
            'city_id'            => $this->city_id,
            'neighborhood'       => $this->neighborhood,
            'number'             => $this->number,
            'complement'         => $this->complement,
            'state_registration' => $this->state_registration,
            'cep'                => $this->cep,
            'ie'                 => $this->ie,
            'indIEDest'          => $this->indIEDest,
            'email'              => $this->email,
            'company'            => new CompanyListResource($this->company)
        ];
    }
}
