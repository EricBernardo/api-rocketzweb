<?php

namespace App\Services;

use App\Entities\Client;
use App\Http\Resources\ClientResource;
use App\Http\Resources\ClientDetailResource;

class ClientServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = Client::class;
    }

    public function paginate()
    {
        return ClientResource::collection($this->entity::paginate());
    }

    public function list($request)
    {

        $result = $this->entity::where(function ($q) use ($request) {

            if ($request->get('companies')) {
                $q->whereIn('company_id', $request->get('companies'));
            }

        })->get();

        return ClientResource::collection($result);
    }

    public function show($id)
    {
        $result = $this->entity::where('id', '=', $id)->get()->first();
        return new ClientDetailResource($result);
    }

    public function create($request)
    {

        $data = $request->all();

        $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj']);
        $data['cpf'] = preg_replace('/\D/', '', $data['cpf']);
        $data['cep'] = preg_replace('/\D/', '', $data['cep']);

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

        $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj']);
        $data['cpf'] = preg_replace('/\D/', '', $data['cpf']);
        $data['cep'] = preg_replace('/\D/', '', $data['cep']);

        if ($request->user()->roles()->first()->name != 'root') {
            $data['company_id'] = $result['company_id'];
        }

        $result->update($data);

        return ['data' => $result];

    }

}

