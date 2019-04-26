<?php

namespace App\Services;

use App\Entities\Company;

class CompanyServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = Company::class;
    }

}

