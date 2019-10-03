<?php

namespace App\Entities;

use App\Models\City;
use App\Models\State;
use App\Scopes\CompanyScope;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'title',
        'cert_file',
        'cert_password',
        'cert_expiration_date',
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
        'image',
        'image_url'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
