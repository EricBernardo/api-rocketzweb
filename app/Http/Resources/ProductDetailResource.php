<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
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
            'title'               => $this->title,
            'price'               => $this->price,
            'cfop'                => $this->cfop,
            'ucom'                => $this->ucom,
            'icms'                => $this->icms,
            'ipi'                 => $this->ipi,
            'pis'                 => $this->pis,
            'cofins'              => $this->cofins,
            'weigh'               => $this->weigh,
            'ncm'                 => $this->ncm,
            'cean'                => $this->cean,
            'product_category_id' => $this->product_category_id,
        ];
    }
}
