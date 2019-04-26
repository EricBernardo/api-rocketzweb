<?php

namespace App\Services;

class DefaultServices
{

    protected $entity;

    public function create($request)
    {

        $result = $this->entity::create($request->all());

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

        $result = $this->entity::where('id', $id)->first();

        $result->update($request->all());

        if (request()->wantsJson()) {
            return $result;
        }

        $response = [
            'message' => 'Updated.',
        ];

        return redirect()->back()->with('success', $response['message']);

    }

    public function delete($id)
    {

        $result = $this->entity::where('id', $id);

        $result->delete();

        if (request()->wantsJson()) {
            return null;
        }

        $response = [
            'message' => 'Deleted.',
        ];

        return redirect()->back()->with('success', $response['message']);

    }

    public function all()
    {
        return $this->entity::all();
    }

    public function show($id)
    {
        return $this->entity::where('id', '=', $id)->get()->first();
    }

    public function paginate()
    {
        return $this->entity::paginate();
    }

}
