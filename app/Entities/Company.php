<?php

namespace App\Entities;

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
    ];

}
