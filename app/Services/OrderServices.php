<?php

namespace App\Services;

use App\Entities\Order;
use App\Entities\Product;

class OrderServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = Order::class;
    }

    public function paginate()
    {
        return $this->entity::orderBy('created_at', 'desc')->with('client')->paginate();
    }

    public function create($request)
    {

        $data = $request->all();

        $data_insert = array();

        $data_insert['client_id'] = $data['client_id'];
        $data_insert['discount'] = $data['discount'];
        $data_insert['subtotal'] = 0;
        $data_insert['total'] = 0;

        $products = [];

        foreach ($data['products'] as $i => $value) {

            $product = Product::where('id', '=', $value['product_id'])->get()->first();

            $data_insert['subtotal'] += ($product['price'] * ($value['quantity']));

            $products[] = [
                'product_id' => $product['id'],
                'price'      => $product['price'],
                'quantity'   => $value['quantity'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $data_insert['total'] = ($data_insert['subtotal'] - $data_insert['discount']);

        $data_insert['paid'] = $data['paid'] ? date('Y-m-d H:i:s') : null;

        return $this->entity::create($data_insert)->products()->sync($products);

    }

    public function update($request, $id)
    {

        $data = $request->all();

        $data_update = array();

        $data_update['client_id'] = $data['client_id'];
        $data_update['discount'] = $data['discount'];
        $data_update['subtotal'] = 0;
        $data_update['total'] = 0;

        $products = [];

        foreach ($data['products'] as $i => $value) {

            $product = Product::where('id', '=', $value['product_id'])->get()->first();

            $data_update['subtotal'] += ($product['price'] * ($value['quantity']));

            $products[] = [
                'product_id' => $product['id'],
                'price'      => $product['price'],
                'quantity'   => $value['quantity'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

        }

        $data_update['total'] = ($data_update['subtotal'] - $data_update['discount']);

        $data_update['paid'] = $data['paid'] ? date('Y-m-d H:i:s') : null;

        $result = $this->entity::where('id', $id)->get()->first();

        $result->update($data_update);

        $result->products()->detach();

        $result->products()->sync($products);

        if (request()->wantsJson()) {
            return $result;
        }

        $response = [
            'message' => 'Created.',
        ];

        return redirect()->back()->with('success', $response['message']);

    }

    public function show($id)
    {
        return $this->entity::with(['products'])->where('id', '=', $id)->get()->first();
    }

}

