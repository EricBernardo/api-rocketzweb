<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Http\Resources\ProfileResource;
use App\User;

class UserServices
{

    public function __construct()
    {
        $this->entity = User::class;
    }

    public function paginate()
    {
        return UserResource::collection($this->entity::where('ID', '!=', request()->user()->id)->paginate());
    }

    public function all($request)
    {
        return UserResource::collection($this->entity::where('ID', '!=', $request->user()->id)->all());
    }

    public function show($id)
    {
        $result = $this->entity::where('id', '=', $id)->get()->first();
        if ($result->roles()) {
            $result['role'] = $result->roles()->first()->name;
        }
        return ['data' => $result];
    }

    public function create($request)
    {

        $data = $request->all();

        $data['password'] = bcrypt($data['password']);

        if (!$request->user()->hasAnyRole('root') && !$request->user()->hasAnyRole('administrator')) {
            $data['client_id'] = $request->user()->client_id;
        }

        if (!$request->user()->hasAnyRole('root')) {
            $data['company_id'] = $request->user()->company_id;
        }

        if ($request->get('role') == 'root') {
            $data['client_id'] = null;
            $data['company_id'] = null;
        }

        if ($request->get('role') == 'administrator') {
            $data['client_id'] = null;
        }

        $result = $this->entity::create($data);

        $result->assignRole($data['role']);

        return ['data' => $result];

    }

    public function update($request, $id)
    {

        $data = $request->all();

        $result = $this->entity::where('id', $id)->first();

        if (isset($data['password']) && $data['password']) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        if (!$request->user()->hasAnyRole('root') && !$request->user()->hasAnyRole('administrator')) {
            $data['client_id'] = $request->user()->client_id;
        }

        if (!$request->user()->hasAnyRole('root')) {
            $data['company_id'] = $request->user()->company_id;
        }

        if ($request->get('role') == 'root') {
            $data['client_id'] = null;
            $data['company_id'] = null;
        }

        if ($request->get('role') == 'administrator') {
            $data['client_id'] = null;
        }

        $result->update($data);

        $result->syncRoles($data['role']);

        return ['data' => $result];

    }

    public function chooseCompany($request, $id)
    {

        $company_id = $request->get('company_id');

        $result = $this->entity::where('id', $id)->first();
        
        $result->update(['company_id' => $company_id]);

        return ['data' => new ProfileResource($result)];

    }

    public function delete($id)
    {
        $result = $this->entity::where('id', $id);
        $result->delete();
        return null;
    }

}

