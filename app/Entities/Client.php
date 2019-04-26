<?php

namespace App\Entities;

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
        'cep'
    ];

}
