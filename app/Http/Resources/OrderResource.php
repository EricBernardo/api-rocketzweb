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
            'id'         => $this->id,
            'price'      => $this->price,
            'paid'       => $this->paid,
            'created_at' => $this->created_at,
            'client'     => new ClientResource($this->client),
        ];
    }
}
