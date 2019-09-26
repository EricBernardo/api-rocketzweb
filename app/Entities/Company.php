<?php

namespace App\Entities;

use App\Models\City;
use App\Models\State;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'title',
        'cert_file',
        'cert_password',
        'fantasy',
        'ie',
        'crt',
        'cnpj',
        'address',
        'number',
        'neighborhood',
        'state_id',
        'city_id',
        'cep',
        'irpj',
        'cofins',
        'pis',
        'csll',
        'iss',
        'phone',
        'complement',
        'image'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope(auth()->guard('api')->user()));
    }

}
