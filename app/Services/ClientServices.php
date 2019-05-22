<?php

namespace App\Services;

use App\Entities\Client;
use App\Http\Resources\ClientResource;

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

    public function create($request)
    {

        $data = $request->all();

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

        $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj']);

        if ($request->user()->roles()->first()->name != 'root') {
            $data['company_id'] = $result['company_id'];
        }

        $result->update($data);

        return ['data' => $result];

    }

}

