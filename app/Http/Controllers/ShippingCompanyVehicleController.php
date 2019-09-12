<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShippingCompanyVehicleRequest;
use App\Services\ShippingCompanyVehicleServices;
use Illuminate\Http\Request;

/**
 * Class ShippingCompanyVehicleController
 * @package App\Http\Controllers
 */
class ShippingCompanyVehicleController extends Controller
{

    private $services;

    /**
     * Create a new controller instance.
     *
     * @param ShippingCompanyVehicleServices $services
     */

    public function __construct(ShippingCompanyVehicleServices $services)
    {
        $this->middleware('auth');
        $this->services = $services;
    }

    public function index()
    {
        return $this->services->paginate();
    }

    public function all(Request $request)
    {
        return $this->services->list($request);
    }

    public function store(ShippingCompanyVehicleRequest $request)
    {        
        return $this->services->create($request);
    }

    public function show($id)
    {
        return $this->services->show($id);
    }

    public function update(ShippingCompanyVehicleRequest $request, $id)
    {
        return $this->services->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->services->delete($id);
    }

}
