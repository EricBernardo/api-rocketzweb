<?php

namespace App\Services;

use App\User;

class UserServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = User::class;
    }

    public function create($request)
    {

        $data = $request->all();

        $data['password'] = bcrypt($data['password']);

        if (!$request->user()->hasAnyRole('root')) {
            $data['client_id'] = $request->user()->client_id;
        }

        $result = $this->entity::create($data);

        $result->assignRole($data['role']);

        if (request()->wantsJson()) {
            return $result;
        }

        $response = [
            'message' => 'Created.',
        ];

        return redirect()->back()->with('success', $response['message']);

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

        if (!$request->user()->hasAnyRole('root')) {
            $data['client_id'] = $request->user()->client_id;
        }

        $result->update($data);

        $result->syncRoles($data['role']);

        if (request()->wantsJson()) {
            return $result;
        }

        $response = [
            'message' => 'Updated.',
        ];

        return redirect()->back()->with('success', $response['message']);

    }

}

