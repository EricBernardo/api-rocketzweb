<?php

namespace App\Services;

use App\Entities\ShippingCompany;
use App\Http\Resources\ShippingCompanyDetailResource;
use App\Http\Resources\ShippingCompanyListResource;

class ShippingCompanyServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = ShippingCompany::class;
    }

    public function paginate()
    {
        return ShippingCompanyListResource::collection($this->entity::paginate());
    }

    public function all()
    {

        $result = $this->entity::where(function ($q) {

            if (request()->get('company_id')) {
                $q->where('company_id', '=', request()->get('company_id'));
            }

        })->get();

        return ShippingCompanyListResource::collection($result);
    }

    public function show($id)
    {
        return new ShippingCompanyDetailResource($this->entity::where('id', '=', $id)->get()->first());
    }

    public function create($request)
    {

        $data = $request->all();

        $data['cpf'] = preg_replace('/\D/', '', $data['cpf']);
        $data['cep'] = preg_replace('/\D/', '', $data['cep']);
        $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj']);

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
        $data['cep'] = preg_replace('/\D/', '', $data['cep']);
        $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj']);

        if ($request->user()->roles()->first()->name != 'root') {
            $data['company_id'] = $result['company_id'];
        }

        $result->update($data);

        return ['data' => $result];

    }

}

