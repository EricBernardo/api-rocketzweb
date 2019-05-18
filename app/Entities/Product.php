<?php

namespace App\Entities;

use App\Http\Controllers\ProductCategoryController;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_category_id',
        'title',
        'price',
    ];
    
    protected $casts = [
        'price' => 'float',
    ];
    
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
    
}
