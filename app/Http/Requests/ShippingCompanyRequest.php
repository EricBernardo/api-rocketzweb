<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingCompanyRequest extends FormRequest
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
            'cpf'          => 'nullable|cpf',
            'address'      => 'required',
            'state_id'     => 'required',
            'city_id'      => 'required',
            'cep'          => 'required',
            'number'       => 'required',
            'neighborhood' => 'required',
            'cnpj'         => 'nullable|cnpj',
        ];
    }
}
