<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
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
            'id'      => $this->id,
            'title'   => $this->title,
            'cfop'    => $this->cfop,
            'business_unit' => $this->business_unit,
            'company' => new CompanyResource($this->company()->first())
        ];
    }
}
