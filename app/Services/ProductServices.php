<?php

namespace App\Services;

use App\Entities\Product;

class ProductServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = Product::class;
    }

}

