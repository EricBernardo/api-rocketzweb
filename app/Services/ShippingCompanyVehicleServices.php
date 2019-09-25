<?php

namespace App\Services;

use App\Entities\ShippingCompanyVehicle;
use App\Http\Resources\ShippingCompanyVehicleResource;

class ShippingCompanyVehicleServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = ShippingCompanyVehicle::class;
    }

    public function paginate()
    {
        return ShippingCompanyVehicleResource::collection($this->entity::paginate());
    }

    public function show($id)
    {
        $result = new ShippingCompanyVehicleResource($this->entity::where('id', '=', $id)->get()->first());
        return ['data' => $result];
    }

}