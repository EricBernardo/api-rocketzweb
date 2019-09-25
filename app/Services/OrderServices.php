<?php

namespace App\Services;

use App\Entities\Order;
use App\Entities\Product;
use App\Http\Resources\OrderResource;

class OrderServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = Order::class;
    }

    public function paginate()
    {
        return OrderResource::collection($this->entity::orderBy('created_at', 'desc')->paginate());
    }

    public function create($request)
    {

        $data = $request->all();

        $data_insert = [];

        if ($request->user()->hasAnyRole('client')) {
            $data_insert['client_id'] = $request->user()->client_id;
        } else {
            $data_insert['client_id'] = $data['client_id'];
        }

        $data_insert['observation'] = $data['observation'];
        $data_insert['discount'] = $data['discount'];
        $data_insert['date'] = $data['date'];
        $data_insert['freight_value'] = $data['freight_value'];
        $data_insert['finNFe'] = $data['finNFe'];
        $data_insert['tpNF'] = $data['tpNF'];
        $data_insert['idDest'] = $data['idDest'];
        $data_insert['tpImp'] = $data['tpImp'];
        $data_insert['tpEmis'] = $data['tpEmis'];
        $data_insert['indFinal'] = $data['indFinal'];
        $data_insert['indPres'] = $data['indPres'];
        $data_insert['indPag'] = $data['indPag'];
        $data_insert['tPag'] = $data['tPag'];
        $data_insert['modFrete'] = $data['modFrete'];
        $data_insert['shipping_company_id'] = $data['shipping_company_id'];
        $data_insert['shipping_company_vehicle_id'] = $data['shipping_company_vehicle_id'];
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
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }

        $data_insert['total'] = ($data_insert['subtotal'] - $data_insert['discount']);

        $data_insert['paid'] = $data['paid'] ? date('Y-m-d H:i:s') : null;

        return $this->entity::create($data_insert)->products()->sync($products);

    }

    public function update($request, $id)
    {

        $result = $this->entity::where('id', $id)->get()->first();

        $data = $request->all();

        $data_update = [];

        if ($request->user()->hasAnyRole('client')) {
            $data_update['client_id'] = $request->user()->client_id;
        } else {
            $data_update['client_id'] = $data['client_id'];
        }

        $data_update['observation'] = $data['observation'];
        $data_update['discount'] = $data['discount'];
        $data_update['date'] = $data['date'];
        $data_update['freight_value'] = $data['freight_value'];
        $data_update['finNFe'] = $data['finNFe'];
        $data_update['tpNF'] = $data['tpNF'];
        $data_update['idDest'] = $data['idDest'];
        $data_update['tpImp'] = $data['tpImp'];
        $data_update['tpEmis'] = $data['tpEmis'];
        $data_update['indFinal'] = $data['indFinal'];
        $data_update['indPres'] = $data['indPres'];
        $data_update['indPag'] = $data['indPag'];
        $data_update['tPag'] = $data['tPag'];
        $data_update['modFrete'] = $data['modFrete'];
        $data_update['shipping_company_id'] = $data['shipping_company_id'];
        $data_update['shipping_company_vehicle_id'] = $data['shipping_company_vehicle_id'];
        $data_update['subtotal'] = 0;
        $data_update['total'] = 0;

        $products_old = [];
        $products_new = [];

        foreach ($data['products'] as $i => $value) {

            if (isset($value['id'])) {

                $data_update['subtotal'] += ($value['price'] * $value['quantity']);

                $products_old[$value['id']] = [
                    'product_id' => $value['product_id'],
                    'price'      => $value['price'],
                    'quantity'   => $value['quantity'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];

            } else {

                $product = Product::where('id', '=', $value['product_id'])->get()->first();

                $data_update['subtotal'] += ($product['price'] * $value['quantity']);

                $products_new[] = [
                    'product_id' => $value['product_id'],
                    'price'      => $product['price'],
                    'quantity'   => $value['quantity'],
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ];

            }

        }

        $result->products()->sync($products_old);
        $result->products()->attach($products_new);

        $data_update['total'] = ($data_update['subtotal'] - $data_update['discount']);

        $data_update['paid'] = $data['paid'] ? date('Y-m-d H:i:s') : null;

        $result->update($data_update);

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
        return new OrderResource($this->entity::where('id', '=', $id)->get()->first());
    }

}

