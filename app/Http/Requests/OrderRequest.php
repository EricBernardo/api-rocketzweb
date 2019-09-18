<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'client_id' => 'required_if:role,!=,client',
            'products' => 'required',
            'shipping_company_id' => 'required',
            'shipping_company_vehicle_id' => 'required',
            'finNFe' => 'required',
            'tpNF' => 'required',
            'idDest' => 'required',
            'tpImp' => 'required',
            'tpEmis' => 'required',
            'indFinal' => 'required',
            'indPres' => 'required',
            'indPag' => 'required',
            'tPag' => 'required',
            'modFrete' => 'required',
        ];
    }
}
