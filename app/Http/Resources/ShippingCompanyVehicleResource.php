<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingCompanyVehicleResource extends JsonResource
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
            'id'                  => $this->id,
            'board'               => $this->board,
            'state_id'            => $this->state_id,
            'shipping_company_id' => $this->shipping_company_id,
            'shipping_company'    => new ShippingCompanyResource($this->shipping_company()->first())
        ];
    }
}
