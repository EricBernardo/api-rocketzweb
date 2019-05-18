<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCategoryRequest;
use App\Services\ProductCategoryServices;

/**
 * Class ProductCategoryController
 * @package App\Http\Controllers
 */
class ProductCategoryController extends Controller
{

    private $services;

    /**
     * Create a new controller instance.
     *
     * @param ProductCategoryServices $services
     */
    public function __construct(ProductCategoryServices $services)
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

    public function store(ProductCategoryRequest $request)
    {
        return $this->services->create($request);
    }

    public function show($id)
    {
        return $this->services->show($id);
    }

    public function update(ProductCategoryRequest $request, $id)
    {
        return $this->services->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->services->delete($id);
    }

}
