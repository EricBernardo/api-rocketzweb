<?php

namespace App\Services;

use Carbon\Carbon;

class DashboardService extends DefaultServices
{

    public function __construct()
    {
        $this->entity = Product::class;
    }

    public function infos($request)
    {
        $data['billing'] = $this->billing($request);
        return ['data' => $data];
    }

    private function billing($request)
    {

        $start_date = $request->get('start_date') ? $request->get('start_date') : Carbon::now()->subDay(7);
        $end_date = $request->get('end_date') ? $request->get('end_date') : Carbon::now();

        $result = \DB::table('orders')
            ->join('clients', function ($join) use ($request) {
                $join->on('clients.id', '=', 'orders.client_id');
            })
            ->selectRaw("
                orders.created_at,
                orders.total,
                orders.paid
            ")
            ->where('orders.created_at', '>=', $start_date)
            ->where('orders.created_at', '<=', $end_date)
            ->orderBy('orders.created_at', 'desc')
            ->get();

        $arr_tmp = [];

        foreach ($result as $item) {

            $date = date('Y-m-d', strtotime($item->created_at));

            if (!isset($arr_tmp[$date]['paid'])) {
                $arr_tmp[$date]['paid'] = 0;
                $arr_tmp[$date]['paid_no'] = 0;
            }

            if ($item->paid) {
                $arr_tmp[$date]['paid'] += ($item->total * 1);
            } else {
                $arr_tmp[$date]['paid_no'] += ($item->total * 1);
            }

        }

        return ['data' => $arr_tmp];
    }

}

