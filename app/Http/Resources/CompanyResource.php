<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'id'            => $this->id,
            'title'         => $this->title,
            'cert_file'     => $this->cert_file,
            'cert_password' => $this->cert_password,
            'fantasy'       => $this->fantasy,
            'ie'            => $this->ie,
            'crt'           => $this->crt,
            'cnpj'          => $this->cnpj,
            'address'       => $this->address,
            'number'        => $this->number,
            'neighborhood'  => $this->neighborhood,
            'state_id'      => $this->state_id,
            'city_id'       => $this->city_id,
            'cep'           => $this->cep,
            'irpj'          => $this->irpj,
            'cofins'        => $this->cofins,
            'pis'           => $this->pis,
            'csll'          => $this->csll,
            'iss'           => $this->iss,
            'phone'         => $this->phone,
        ];
    }
}
