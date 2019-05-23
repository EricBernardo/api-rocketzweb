<?php

namespace App\Services;

use App\Entities\Product;
use App\Http\Resources\ProductResource;

class ProductServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = Product::class;
    }

    public function paginate()
    {
        return ProductResource::collection($this->entity::paginate());
    }

    public function all()
    {
        return ProductResource::collection($this->entity::all());
    }

}

