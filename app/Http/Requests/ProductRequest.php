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
            'cfop'                => 'required',
            'ucom'                => 'required',
            'icms'                => 'required',
            'ipi'                 => 'required',
            'pis'                 => 'required',
            'cofins'              => 'required',
            'weigh'               => 'required',
            'ncm'                 => 'required'
        ];
    }
}
