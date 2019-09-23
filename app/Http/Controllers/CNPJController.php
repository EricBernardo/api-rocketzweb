<?php

namespace App\Http\Controllers;

use App\Http\Requests\CNPJRequest;
use App\Services\CNPJServices;

/**
 * Class CompanyController
 * @package App\Http\Controllers
 */
class CNPJController extends Controller
{

    private $services;

    public function __construct(CNPJServices $services)
    {
//        $this->middleware('auth');
        $this->services = $services;
    }

    public function show(CNPJRequest $request)
    {
        return $this->services->show($request);
    }

}
