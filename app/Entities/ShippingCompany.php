<?php

namespace App\Entities;

use App\Scopes\ShippingCompanyScope;
use Illuminate\Database\Eloquent\Model;

class ShippingCompany extends Model
{
    protected $fillable = [
        'title',
        'cpf',
        'ie',
        'address',
        'state_id',
        'city_id',
        'company_id',
        'cep',
        'number',
        'neighborhood',
        'cnpj',
        'fantasy',
        'complement'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ShippingCompanyScope(auth()->guard('api')->user()));
    }

}
