<?php

namespace App\Services;

class DefaultServices
{

    protected $entity;

    public function all()
    {
        $result = $this->entity::all();
        return ['data' => $result];
    }

    public function paginate()
    {
        return $this->entity::paginate();
    }

    public function show($id)
    {
        $result = $this->entity::where('id', '=', $id)->get()->first();
        return ['data' => $result];
    }

    public function create($request)
    {
        $result = $this->entity::create($request->all());
        return ['data' => $result];
    }

    public function update($request, $id)
    {
        $result = $this->entity::where('id', $id)->first();
        $result->update($request->all());
        return ['data' => $result];
    }

    public function delete($id)
    {
        $result = $this->entity::where('id', $id);
        $result->delete();
        return null;
    }

}
