<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
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
            'id'       => $this->id,
            'title'    => $this->title,
            'price'    => $this->price,
            'category' => new ProductCategoryResource($this->category),
        ];
    }
}
