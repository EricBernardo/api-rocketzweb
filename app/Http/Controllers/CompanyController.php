<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyFileRequest;
use App\Http\Requests\CompanyImageRequest;
use App\Http\Requests\CompanyRequest;
use App\Services\CompanyServices;
use Illuminate\Http\Request;

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

    public function all()
    {
        return $this->services->all();
    }

    public function store(CompanyRequest $request)
    {
        return $this->services->create($request);
    }

    public function store_file(CompanyFileRequest $request)
    {
        return $this->services->create_file($request);
    }

    public function store_image(CompanyImageRequest $request)
    {
        return $this->services->create_image($request);
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

    public function destroy_file(Request $request)
    {
        return $this->services->delete_file($request->get('id'));
    }


}
