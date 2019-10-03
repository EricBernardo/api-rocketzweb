<?php

namespace App\Services;

use App\Entities\ShippingCompanyVehicle;
use App\Http\Resources\ShippingCompanyVehicleDetailResource;
use App\Http\Resources\ShippingCompanyVehicleListResource;

class ShippingCompanyVehicleServices extends DefaultServices
{

    public function __construct()
    {
        $this->entity = ShippingCompanyVehicle::class;
    }

    public function paginate()
    {
        return ShippingCompanyVehicleListResource::collection($this->entity::paginate());
    }

    public function all()
    {
        return ShippingCompanyVehicleListResource::collection($this->entity::all());
    }

    public function show($id)
    {
        return new ShippingCompanyVehicleDetailResource($this->entity::where('id', '=', $id)->get()->first());
    }

}