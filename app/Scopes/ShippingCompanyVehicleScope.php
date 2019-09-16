<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ShippingCompanyVehicleScope implements Scope
{

    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function apply(Builder $builder, Model $model)
    {

        $builder->whereHas('shipping_company');

        $shipping_company_id = request()->get('shipping_company_id');

        if($shipping_company_id) {
            $builder->where('shipping_company_id', $shipping_company_id);
        }

    }
}
