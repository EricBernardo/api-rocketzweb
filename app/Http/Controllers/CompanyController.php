<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Services\CompanyServices;

/**
 * Class CompanyController
 * @package App\Http\Controllers
 */
class CompanyController extends Controller
{

    private $services;

    /**
     * Create a new controller instance.
     *
     * @param CompanyServices $services
     */
    public function __construct(CompanyServices $services)
    {
        $this->middleware('auth');
        $this->services = $services;
    }

    public function index()
    {
        return $this->services->paginate();
    }

    public function store(CompanyRequest $request)
    {
        return $this->services->create($request);
    }

    public function show($id)
    {
        return $this->services->show($id);
    }

    public function update(CompanyRequest $request, $id)
    {
        return $this->services->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->services->delete($id);
    }


}
