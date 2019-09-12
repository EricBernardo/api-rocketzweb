<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'title' => 'required',
            'fantasy' => 'required',
            'ie' => 'required',
            'crt' => 'required',
            'cnpj' => 'required|cnpj',
            'address' => 'required',
            'number' => 'required',
            'neighborhood' => 'required',
            'state_id' => 'required',            
            'city_id' => 'required',            
            'cep' => 'required',
        ];
    }
}
