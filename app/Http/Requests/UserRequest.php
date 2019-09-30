<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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

        $roles = [
                'name'       => 'required',
                'email'      => 'required|email',
                'password'   => 'confirmed',
                'client_id'  => 'required_if:role,client',
                'companies' => 'required_if:role,administrator|required_if:role,client|array'
            ];

        if(request()->get('role') == 'client') {
            $roles['companies'] = 'required_if:role,administrator|required_if:role,client|array|between::1,1';
        }

        return $roles;
    }
}
