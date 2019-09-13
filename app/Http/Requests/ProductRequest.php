<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'product_category_id' => 'required',
            'title'               => 'required',
            'price'               => 'required|numeric',
            'cfop' => 'required',
            'ucom' => 'required',
            'csosn' => 'required',
            'ipi_ipint_cst' => 'required',
            'pis_ipint_cst' => 'required',
            'cofins_cofinsnt_cst' => 'required',
            'weigh' => 'required'
        ];
    }
}
