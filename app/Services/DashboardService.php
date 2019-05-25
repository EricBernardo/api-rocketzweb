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
        $data['billings'] = $this->billing($request);
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
            ->where('orders.created_at', '>=', $start_date . ' 00:00:00')
            ->where('orders.created_at', '<=', $end_date . ' 23:59:59')
            ->orderBy('orders.created_at', 'desc')
            ->get();

        $data = [];

        foreach ($result as $item) {

            $date = date('Y-m-d', strtotime($item->created_at));

            if (!isset($data[$date]['paid'])) {
                $data[$date]['paid'] = 0;
                $data[$date]['paid_no'] = 0;
            }

            if ($item->paid) {
                $data[$date]['paid'] += ($item->total * 1);
            } else {
                $data[$date]['paid_no'] += ($item->total * 1);
            }

        }

        return $data;
    }

}

