<?php

namespace App\Services;

use App\Entities\Client;
use App\Entities\Order;

class DashboardService extends DefaultServices
{
    
    public function __construct()
    {
        $this->entity = Product::class;
    }
    
    public function infos($request)
    {
        $data = [
            'input'       => 0,
            'output'      => 0,
            'balance'     => 0,
            'new_clients' => 0,
            'input'       => 0,
            'orders'      => 0,
        ];
        
        $order = Order::class;
        
        if ($request->has('date_start') && $request->has('date_end')) {
            
            $data['orders'] = $order::where([
                ['created_at', '>=', $request->get('date_start') . ' 00:00:00'],
                ['created_at', '<=', $request->get('date_end') . ' 23:00:00']
            ])->count();
            
        } else {
            
            $data['orders'] = $order::count();
            
        }
        
        $client = Client::class;
        
        if ($request->has('date_start') && $request->has('date_end')) {
            
            $data['new_clients'] = $client::where([
                ['created_at', '>=', $request->get('date_start') . ' 00:00:00'],
                ['created_at', '<=', $request->get('date_end') . ' 23:00:00']
            ])->count();
            
        } else {
            
            $data['new_clients'] = $client::count();
            
        }
        
        return $data;
        
    }
    
}

