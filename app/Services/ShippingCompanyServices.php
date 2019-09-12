<?php

namespace App\Services;

use App\Entities\ShippingCompany;
use App\Http\Resources\ShippingCompanyResource;

class ShippingCompanyServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = ShippingCompany::class;
    }

    public function paginate()
    {
        return ShippingCompanyResource::collection($this->entity::paginate());
    }

    public function create($request)
    {

        $data = $request->all();

        $data['cpf'] = preg_replace('/\D/', '', $data['cpf']);

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

        $data['cpf'] = preg_replace('/\D/', '', $data['cpf']);

        if ($request->user()->roles()->first()->name != 'root') {
            $data['company_id'] = $result['company_id'];
        }

        $result->update($data);

        return ['data' => $result];

    }

}

