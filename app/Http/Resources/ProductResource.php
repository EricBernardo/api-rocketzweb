<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'title'    => $this->title,
            'price'    => $this->price,
            'cfop' => $this->cfop,
            'ucom' => $this->ucom,
            'csosn' => $this->csosn,
            'ipi_ipint_cst' => $this->ipi_ipint_cst,
            'pis_ipint_cst' => $this->pis_ipint_cst,
            'cofins_cofinsnt_cst' => $this->cofins_cofinsnt_cst,
            'category' => new ProductCategoryResource($this->category),
        ];
    }
}
