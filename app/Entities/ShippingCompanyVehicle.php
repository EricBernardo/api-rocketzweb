<?php

namespace App\Entities;

use App\Scopes\ProductCategoryVehicleScope;
use Illuminate\Database\Eloquent\Model;

class ShippingCompanyVehicle extends Model
{
    protected $fillable = [
        'board',
        'state_id',
        'shipping_company_id'
    ];

    public function shipping_company()
    {
        return $this->belongsTo(ShippingCompany::class);
    }

}
