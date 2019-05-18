<?php

namespace App\Services;

use App\Entities\ProductCategory;

class ProductCategoryServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = ProductCategory::class;
    }

}

