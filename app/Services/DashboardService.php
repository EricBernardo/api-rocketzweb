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

                if ($request->user()->hasAnyRole('client')) {
                    $join->where('clients.id', '=', $request->user()->client_id);
                }

                if ($request->get('client_id')) {
                    $join->where('clients.id', '=', $request->get('client_id'));
                }

            })
            ->join('companies', function ($join) use ($request) {
                $join->on('companies.id', '=', 'clients.company_id');

                if ($request->user()->hasAnyRole('administrator')) {
                    $join->where('companies.id', '=', $request->user()->company_id);
                }

                if ($request->user()->hasAnyRole('client')) {
                    $join->where('companies.id', '=', $request->user()->company_id);
                }

                if ($request->get('company_id')) {
                    $join->where('companies.id', '=', $request->get('company_id'));
                }

            })
            ->selectRaw("
                orders.date,
                orders.total,
                orders.paid
            ")
            ->where('orders.date', '>=', $start_date)
            ->where('orders.date', '<=', $end_date)
            ->orderBy('orders.date', 'asc')
            ->get();

        $data = [];

        foreach ($result as $item) {

            $date = date('Y-m-d', strtotime($item->date));

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

