<?php

namespace App\Entities;

use App\Scopes\ShippingCompanyVehicleScope;
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

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ShippingCompanyVehicleScope(auth()->guard('api')->user()));
    }

}
