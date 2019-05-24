<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{

    private $services;

    /**
     * Create a new controller instance.
     *
     * @param DashboardServices $services
     */
    public function __construct(DashboardService $services)
    {
        $this->middleware('auth');
        $this->services = $services;
    }

    public function index(Request $request)
    {
        return $this->services->infos($request);
    }

}
