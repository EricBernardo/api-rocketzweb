<?php

namespace App\Services;

use App\Entities\Company;
use Illuminate\Support\Facades\Storage;

class CompanyServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = Company::class;
    }

    public function create_file($request)
    {

        if(substr($_FILES['file']['name'], -4) != '.pfx') {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => ['file' => ["O arquivo deve ser do tipo: pfx."]]
            ])->setStatusCode(422);
        }

        $result = null;

        if($request->file('file')) {

            $result = $request->file('file')->store(
                'certs',
                's3'
            );

        }

        return ['data' => $result];

    }

}

