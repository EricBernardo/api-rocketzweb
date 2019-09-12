<?php

namespace App\Services;

use App\Entities\Company;
use Illuminate\Support\Facades\Storage;
use NFePHP\Common\Certificate;

class CompanyServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = Company::class;
    }

    public function create($request)
    {

        $data = $request->all();

        $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj']);
        
        $result = $this->entity::create($data);

        return ['data' => $result];

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

    public function update($request, $id)
    {

        $result = $this->entity::where('id', $id)->first();

        if($request->get('cert_file')) {

            $certDigital = Storage::disk('s3')->get($request->get('cert_file'));

            $isExpired = Certificate::readPfx($certDigital, $request->get('cert_password'))->isExpired();

            if($isExpired) {
                return response()->json([
                    'message' => "O arquivo .PFX expirou."
                ])->setStatusCode(500);
            }

        }

        if($result['cert_file'] && $result['cert_file'] != $request->get('cert_file')) {
            $this->delete_file($result['cert_file']);
        }

        $data = $request->all();

        $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj']);

        $result->update($data);

        return ['data' => $result];

    }

    public function delete_file($id) {
        return ['data' => Storage::disk('s3')->delete($id)];
    }

}

