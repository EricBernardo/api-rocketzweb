<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'        => 'required',            
            'address'      => 'required',
            'state_id'     => 'required',
            'city_id'      => 'required',
            'neighborhood' => 'required',
            'number'       => 'required|integer',
            'cep'          => 'required',
            'cpf'          => 'nullable|cpf',
            'cnpj'         => 'nullable|cnpj',
        ];
    }
}
