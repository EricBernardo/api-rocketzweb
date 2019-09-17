<?php

namespace App\Entities;

use App\Models\City;
use App\Models\State;
use App\Scopes\ClientScope;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'title',
        'cnpj',
        'address',
        'phone',
        'state_id',
        'city_id',
        'neighborhood',
        'number',
        'complement',
        'state_registration',
        'cep',
        'company_id',
        'ie',
        'indIEDest',
        'email'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

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

        static::addGlobalScope(new ClientScope(auth()->guard('api')->user()));
    }

}
