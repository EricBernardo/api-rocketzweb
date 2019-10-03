<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
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
            'id'                          => $this->id,
            'total'                       => $this->total,
            'paid'                        => $this->paid,
            'date'                        => $this->date,
            'discount'                    => $this->discount,
            'observation'                 => $this->observation,
            'freight_value'               => $this->freight_value,
            'shipping_company_id'         => $this->shipping_company_id,
            'shipping_company_vehicle_id' => $this->shipping_company_vehicle_id,
            'finNFe'                      => $this->finNFe,
            'tpNF'                        => $this->tpNF,
            'idDest'                      => $this->idDest,
            'tpImp'                       => $this->tpImp,
            'tpEmis'                      => $this->tpEmis,
            'indFinal'                    => $this->indFinal,
            'indPres'                     => $this->indPres,
            'indPag'                      => $this->indPag,
            'tPag'                        => $this->tPag,
            'modFrete'                    => $this->modFrete,
            'receipt'                     => $this->receipt,
            'xml'                         => $this->xml,
            'client'                      => new ClientListResource($this->client),
            'products'                    => $this->products,
        ];
    }
}
