<?php

namespace App\Services;

use App\Http\Resources\UserListResource;
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
        return UserListResource::collection($this->entity::where('ID', '!=', request()->user()->id)->whereHas('roles', function($q) {
            if(request()->user()->roles->first()->name == 'client') {
                $q->where('name', '=', 'client');
            }
            if(request()->user()->roles->first()->name == 'administrator') {
                $q->where('name', '=', 'client');
                $q->orWhere('name', '=', 'administrator');
            }
        })->paginate());
    }

    public function all($request)
    {
        return UserListResource::collection($this->entity::where('ID', '!=', $request->user()->id)->whereHas('roles', function($q) {
            if(request()->user()->roles->first()->name == 'client') {
                $q->where('name', '=', 'client');
            }
            if(request()->user()->roles->first()->name == 'administrator') {
                $q->where('name', '=', 'client');
                $q->orWhere('name', '=', 'administrator');
            }
        })->all());
    }

    public function show($id)
    {
        $result = $this->entity::where('id', '=', $id)->get()->first();
        if ($result->roles()) {
            $result['role'] = $result->roles()->first()->name;
        }
        return ['data' => new UserListResource($result)];
    }

    public function create($request)
    {

        $data = $request->all();

        $data['password'] = bcrypt($data['password']);

        if (!$request->user()->hasAnyRole('root') && !$request->user()->hasAnyRole('administrator')) {
            $data['client_id'] = $request->user()->client_id;
        }

        if ($request->get('role') == 'root' || $request->get('role') == 'administrator') {
            $data['client_id'] = null;
        }

        $result = $this->entity::create($data);

        $result->companies()->sync($data['companies']);

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

        if ($request->get('role') == 'root' || $request->get('role') == 'administrator') {
            $data['client_id'] = null;
        }

        $result->update($data);

        $result->companies()->sync($data['companies']);

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

