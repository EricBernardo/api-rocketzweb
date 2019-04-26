<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Services\ProductServices;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{

    private $services;

    /**
     * Create a new controller instance.
     *
     * @param ProductServices $services
     */
    public function __construct(ProductServices $services)
    {
        $this->middleware('auth');
        $this->services = $services;
    }

    public function index()
    {
        $results = $this->services->paginate();
        return view('layouts.pages.product.index', compact('results'));
    }

    public function all()
    {
        $results = $this->services->all();
        if (request()->wantsJson()) {
            return $results;
        }
    }


    public function create()
    {
        return view('layouts.pages.product.create');
    }

    public function store(ProductRequest $request)
    {
        return $this->services->create($request);
    }

    public function edit($id)
    {
        $result = $this->services->show($id);
        return view('layouts.pages.product.edit', compact('result'));
    }

    public function update(ProductRequest $request, $id)
    {
        return $this->services->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->services->delete($id);
    }


}
