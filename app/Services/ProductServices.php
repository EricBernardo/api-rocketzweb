<?php

namespace App\Services;

use App\Entities\Product;

class ProductServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = Product::class;
    }

    public function create($request)
    {

        $data = $request->all();

        $data['price'] = str_replace(',', '.', (str_replace('.', '', $data['price'])));

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

        $data['price'] = str_replace(',', '.', (str_replace('.', '', $data['price'])));

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

