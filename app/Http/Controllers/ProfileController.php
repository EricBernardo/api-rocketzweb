<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\ProfileResource;
use App\Services\UserServices;
use Illuminate\Http\Request;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class ProfileController extends Controller
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

    public function index(Request $request)
    {
        return ['data' => new ProfileResource($request->user())];
    }

    public function update(Request $request)
    {
        return $this->services->update($request, $request->user()->id);
    }

}
