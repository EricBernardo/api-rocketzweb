<?php

namespace App\Entities;

use App\Scopes\ProductCategoryScope;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'title',
        'company_id',
        'cfop',
        'business_unit'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ProductCategoryScope(auth()->guard('api')->user()));
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
