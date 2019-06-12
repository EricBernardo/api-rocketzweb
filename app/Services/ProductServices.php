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

    public function list($request)
    {

        $result = $this->entity::whereHas('category', function($q) use ($request) {

            if ($request->get('company_id')) {
                $q->where('company_id', '=', $request->get('company_id'));
            }

        })->get();

        return ProductResource::collection($result);
    }

}

