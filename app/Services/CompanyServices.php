<?php

namespace App\Services;

use App\Entities\Company;

class CompanyServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = Company::class;
    }

    public function create_file()
    {

        return 'vaivai.jpx';

        $result = null;

        if(request()->file('file')) {

            $result = request()->file('file')->store(
                'certs',
                's3'
            );

        }

        return ['data' => $result];

    }

}

