<?php

namespace App\Services;

use App\Entities\Product;

class ProductServices extends DefaultServices
{
    
    public function __construct()
    {
        $this->entity = Product::class;
    }
    
    public function paginate()
    {
        return $this->entity::with(['category'])->paginate();
    }
    
}

