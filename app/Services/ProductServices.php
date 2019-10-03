<?php

namespace App\Services;

use App\Entities\Product;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductListResource;

class ProductServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = Product::class;
    }

    public function paginate()
    {
        return ProductListResource::collection($this->entity::paginate());
    }

    public function all()
    {

        $result = $this->entity::whereHas('category', function ($q) {

            if (request()->get('company_id')) {
                $q->where('company_id', '=', request()->get('company_id'));
            }

        })->get();

        return ProductListResource::collection($result);
    }

    public function show($id)
    {
        return new ProductDetailResource($this->entity::where('id', '=', $id)->get()->first());
    }

}

