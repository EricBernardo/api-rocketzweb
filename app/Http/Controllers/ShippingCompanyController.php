<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShippingCompanyRequest;
use App\Services\ShippingCompanyServices;
use Illuminate\Http\Request;

/**
 * Class ShippingCompanyController
 * @package App\Http\Controllers
 */
class ShippingCompanyController extends Controller
{

    private $services;

    /**
     * Create a new controller instance.
     *
     * @param ShippingCompanyServices $services
     */

    public function __construct(ShippingCompanyServices $services)
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

    public function store(ShippingCompanyRequest $request)
    {        
        return $this->services->create($request);
    }

    public function show($id)
    {
        return $this->services->show($id);
    }

    public function update(ShippingCompanyRequest $request, $id)
    {
        return $this->services->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->services->delete($id);
    }

}
