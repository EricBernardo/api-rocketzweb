<?php

namespace App\Services;

use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use App\User;

class ProfileServices
{

    public function __construct()
    {
        $this->entity = User::class;
    }

    public function show($id)
    {
        $result = $this->entity::where('id', '=', $id)->get()->first();
        if ($result->roles()) {
            $result['role'] = $result->roles()->first()->name;
        }
        return ['data' => new UserResource($result)];
    }

    public function update($request)
    {

        $data = $request->all();

        $result = $request->user();

        if (isset($data['password']) && $data['password']) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $result->update($data);

        return ['data' => new ProfileResource($result)];

    }

    public function chooseCompany($request)
    {

        $company_id = $request->get('company_id');

        $result = $request->user();

        $result->update(['company_id' => $company_id]);

        return ['data' => new ProfileResource($result)];

    }

}

