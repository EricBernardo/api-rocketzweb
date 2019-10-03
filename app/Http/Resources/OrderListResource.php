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
            'id'      => $this->id,
            'total'   => $this->total,
            'date'    => $this->date,
            'receipt' => $this->receipt,
            'xml'     => $this->xml,
            'client'  => new ClientListResource($this->client)
        ];
    }
}
