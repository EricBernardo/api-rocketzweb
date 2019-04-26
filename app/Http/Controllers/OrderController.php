<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrderServices;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{

    private $services;

    /**
     * Create a new controller instance.
     *
     * @param OrderServices $services
     */
    public function __construct(OrderServices $services)
    {
        $this->middleware('auth');
        $this->services = $services;
    }

    public function index()
    {
        return $this->services->paginate();
    }

    public function store(OrderRequest $request)
    {
        return $this->services->create($request->all());
    }

    public function show($id)
    {
        return $this->services->show($id);
    }

    public function update(OrderRequest $request, $id)
    {
        return $this->services->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->services->delete($id);
    }

}
