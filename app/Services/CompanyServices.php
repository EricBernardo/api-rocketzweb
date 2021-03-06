<?php

namespace App\Services;

use App\Entities\Company;
use App\Http\Resources\CompanyDetailResource;
use App\Http\Resources\CompanyListResource;
use Illuminate\Support\Facades\Storage;
use NFePHP\Common\Certificate;

class CompanyServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = Company::class;
    }

    public function all()
    {
        if (request()->user()->roles->first()->name == 'root') {
            $result = $this->entity::all();
        } else {
            $result = $this->entity::whereHas('users', function ($q) {
                $q->where('user_id', '=', request()->user()->id);
            })->get();
        }
        return CompanyListResource::collection($result);
    }

    public function paginate()
    {
        return CompanyListResource::collection($this->entity::where('companies.id', '=', request()->user()->company_id)->paginate());
    }

    public function show($id)
    {
        return new CompanyDetailResource($this->entity::where('companies.id', '=', $id)->get()->first());
    }

    public function create($request)
    {

        $data = $request->all();

        $data['cert_expiration_date'] = null;

        if ($data['cert_file']) {

            $certDigital = Storage::disk('s3')->get($data['cert_file']);

            $certificate = Certificate::readPfx($certDigital, $data['cert_password']);

            $data['cert_expiration_date'] = $certificate->getValidTo();

            if ($certificate->isExpired()) {
                return response()->json([
                    'message' => "O arquivo .PFX expirou."
                ])->setStatusCode(500);
            }

        }

        $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj']);
        $data['cep'] = preg_replace('/\D/', '', $data['cep']);

        if (substr($data['image'], 0, 3) == 'tmp') {
            $to = 'companies/' . $data['cnpj'] . substr($data['image'], 3);
            if (Storage::disk('s3')->move($data['image'], $to)) {
                $data['image'] = $to;
            }
        }

        if (substr($data['cert_file'], 0, 3) == 'tmp') {
            $to = 'companies/' . $data['cnpj'] . substr($data['cert_file'], 3);
            if (Storage::disk('s3')->move($data['cert_file'], $to)) {
                $data['cert_file'] = $to;
            }
        }

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
                'tmp/certificate',
                's3'
            );
        }

        return ['data' => $result];

    }

    public function create_image($request)
    {

        $result = null;

        if ($request->file('file')) {
            $result = Storage::disk('s3')->put('tmp/image', $request->file('file'), 'public');
        }

        return [
            'data' => [
                'image_url' => $result ? getenv('AWS_URL_PUBLIC') . $result : null,
                'image'     => $result
            ]
        ];

    }

    public function update($request, $id)
    {

        $data = $request->all();

        $data['cert_expiration_date'] = null;

        $result = $this->entity::where('id', $id)->where('companies.id', '=', $request->user()->company_id)->first();

        if ($request->get('cert_file')) {

            $certDigital = Storage::disk('s3')->get($request->get('cert_file'));

            $certificate = Certificate::readPfx($certDigital, $request->get('cert_password'));

            $data['cert_expiration_date'] = $certificate->getValidTo();

            if ($certificate->isExpired()) {
                return response()->json([
                    'message' => "O arquivo .PFX expirou."
                ])->setStatusCode(500);
            }

        }

        if ($result['cert_file'] && $result['cert_file'] != $request->get('cert_file')) {
            $this->delete_file($result['cert_file']);
        }

        if ($result['image'] && $result['image'] != $request->get('image')) {
            $this->delete_file($result['image']);
        }

        $data['cnpj'] = preg_replace('/\D/', '', $data['cnpj']);
        $data['cep'] = preg_replace('/\D/', '', $data['cep']);

        if (substr($data['image'], 0, 3) == 'tmp') {
            $to = 'companies/' . $data['cnpj'] . substr($data['image'], 3);
            if (Storage::disk('s3')->move($data['image'], $to)) {
                $data['image'] = $to;
                $data['image_url'] = getenv('AWS_URL_PUBLIC') . $to;
            }
        }

        if (substr($data['cert_file'], 0, 3) == 'tmp') {
            $to = 'companies/' . $data['cnpj'] . substr($data['cert_file'], 3);
            if (Storage::disk('s3')->move($data['cert_file'], $to)) {
                $data['cert_file'] = $to;
            }
        }

        $result->update($data);

        return ['data' => $result];

    }

    public function delete_file($id)
    {
        return ['data' => Storage::disk('s3')->delete($id)];
    }

}

