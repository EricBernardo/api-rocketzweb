<?php

namespace App\Services;

use App\Entities\ProductCategory;

class ProductCategoryServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = ProductCategory::class;
    }

    public function create($request)
    {

        $data = $request->all();

        if ($request->user()->roles()->first()->name != 'root') {
            $data['company_id'] = $request->user()->company_id;
        }

        $result = $this->entity::create($data);

        return ['data' => $result];

    }

    public function update($request, $id)
    {

        $data = $request->all();

        $result = $this->entity::where('id', $id)->first();

        if ($request->user()->roles()->first()->name != 'root') {
            $data['company_id'] = $result['company_id'];
        }

        $result->update($data);

        return ['data' => $result];

    }

}

