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
            'id'       => $this->id,
            'total'    => $this->total,
            'paid'     => $this->paid,
            'date'     => $this->date,
            'client'   => new ClientResource($this->client),
            'products' => $this->products,
        ];
    }
}
