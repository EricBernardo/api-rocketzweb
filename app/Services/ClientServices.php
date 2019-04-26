<?php

namespace App\Services;

use App\Entities\Client;

class ClientServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = Client::class;
    }

    public function create($request)
    {

        $data = $request->all();

        $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj']);

        $result = $this->entity::create($data);

        if (request()->wantsJson()) {
            return $result;
        }

        $response = [
            'message' => 'Created.',
        ];

        return redirect()->back()->with('success', $response['message']);

    }

    public function update($request, $id)
    {

        $data = $request->all();

        $result = $this->entity::where('id', $id)->first();

        $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj']);

        $result->update($data);

        if (request()->wantsJson()) {
            return $result;
        }

        $response = [
            'message' => 'Updated.',
        ];

        return redirect()->back()->with('success', $response['message']);

    }

}

