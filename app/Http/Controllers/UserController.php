<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserServices;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{

    private $services;

    /**
     * Create a new controller instance.
     *
     * @param UserServices $services
     */
    public function __construct(UserServices $services)
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

    public function store(UserRequest $request)
    {
        return $this->services->create($request);
    }

    public function show($id)
    {
        return $this->services->show($id);
    }

    public function update(UserRequest $request, $id)
    {
        return $this->services->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->services->delete($id);
    }

}