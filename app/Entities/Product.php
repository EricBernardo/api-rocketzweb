<?php

namespace App\Entities;

use App\Scopes\ProductScope;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'id',
        'product_category_id',
        'title',
        'price',
        'cfop',
        'ucom',
        'icms',
        'ipi',
        'pis',
        'cofins',
        'weigh',
        'ncm',
        'cean'
    ];

    protected $casts = [
        'price' => 'float',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ProductScope(auth()->guard('api')->user()));
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

}
