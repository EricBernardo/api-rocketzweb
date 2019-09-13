<?php

namespace App\Entities;

use App\Scopes\ProductScope;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_category_id',
        'title',
        'price',
        'cfop',
        'ucom',
        'csosn',
        'ipi_ipint_cst',
        'pis_ipint_cst',
        'cofins_cofinsnt_cst',
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
