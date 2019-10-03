<?php

namespace App\Services;

use App\Entities\ProductCategory;
use App\Http\Resources\ProductCategoryResource;

class ProductCategoryServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = ProductCategory::class;
    }

    public function paginate()
    {
        return ProductCategoryResource::collection($this->entity::paginate());
    }

    public function all()
    {
        return ProductCategoryResource::collection($this->entity::all());
    }

    public function show($id)
    {
        return new ProductCategoryResource($this->entity::where('id', '=', $id)->get()->first());
    }

    public function create($request)
    {

        $data = $request->all();

        $data['company_id'] = $request->user()->company_id;

        $result = $this->entity::create($data);

        return ['data' => $result];

    }

    public function update($request, $id)
    {

        $data = $request->all();

        $result = $this->entity::where('id', $id)->first();

        $result->update($data);

        return ['data' => $result];

    }

}

