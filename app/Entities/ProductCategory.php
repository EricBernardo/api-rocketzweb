<?php

namespace App\Entities;

use App\Scopes\ProductCategoryScope;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'title',
        'company_id'
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope(new ProductCategoryScope(auth()->guard('api')->user()));
    }
    
    
}
