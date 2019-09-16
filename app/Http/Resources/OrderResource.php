<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'id'          => $this->id,
            'total'       => $this->total,
            'paid'        => $this->paid,
            'date'        => $this->date,
            'discount'    => $this->discount,
            'observation' => $this->observation,
            'freight_value' => $this->freight_value,
            'shipping_company_id' => $this->shipping_company_id,
            'shipping_company_vehicle_id' => $this->shipping_company_vehicle_id,
            'client'      => new ClientResource($this->client),
            'products'    => $this->products,
        ];
    }
}
