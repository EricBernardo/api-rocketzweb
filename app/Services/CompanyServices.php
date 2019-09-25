<?php

namespace App\Services;

use App\Entities\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use NFePHP\Common\Certificate;

class CompanyServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = Company::class;
    }

    public function show($id)
    {

        $result = $this->entity::where('id', '=', $id)->get()->first();

        $result['temporary_url'] = null;

        if ($result['image']) {
            $result['temporary_url'] = Storage::disk('s3')->temporaryUrl($result['image'], Carbon::now()->addMinutes(5));
        }

        return ['data' => $result];
    }

    public function create($request)
    {

        $data = $request->all();

        $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj']);
        $data['cep'] = preg_replace('/\D/', '', $data['cep']);

        $result = $this->entity::create($data);

        return ['data' => $result];

    }

    public function create_file($request)
    {

        if (substr($_FILES['file']['name'], -4) != '.pfx') {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => ['file' => ["O arquivo deve ser do tipo: pfx."]]
            ])->setStatusCode(422);
        }

        $result = null;

        if ($request->file('file')) {
            $result = $request->file('file')->store(
                'certs',
                's3'
            );
        }

        return ['data' => $result];

    }

    public function create_image($request)
    {

        $result = null;

        if ($request->file('file')) {
            $result = $request->file('file')->store(
                'image',
                's3'
            );
        }

        return [
            'data' => [
                'temporary_url' => Storage::disk('s3')->temporaryUrl($result, Carbon::now()->addMinutes(5)),
                'image'         => $result
            ]
        ];

    }

    public function update($request, $id)
    {

        $result = $this->entity::where('id', $id)->first();

        if ($request->get('cert_file')) {

            $certDigital = Storage::disk('s3')->get($request->get('cert_file'));

            $isExpired = Certificate::readPfx($certDigital, $request->get('cert_password'))->isExpired();

            if ($isExpired) {
                return response()->json([
                    'message' => "O arquivo .PFX expirou."
                ])->setStatusCode(500);
            }

        }

        if ($result['cert_file'] && $result['cert_file'] != $request->get('cert_file')) {
            $this->delete_file($result['cert_file']);
        }

        if ($result['image'] && $result['image'] != $request->get('image')) {
            Storage::disk('s3')->delete($result['image']);
        }

        $data = $request->all();

        $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj']);
        $data['cep'] = preg_replace('/\D/', '', $data['cep']);

        $result->update($data);

        return ['data' => $result];

    }

    public function delete_file($id)
    {
        return ['data' => Storage::disk('s3')->delete($id)];
    }

}

