<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'company'   => new CompanyDetailResource($this->company),
            'companies' => CompanyListResource::collection($this->companies),
            'role'      => $this->roles()->first()->name
        ];
    }
}
