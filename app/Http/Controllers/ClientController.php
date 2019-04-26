<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\City;
use App\Models\State;
use App\Services\ClientServices;

/**
 * Class ClientController
 * @package App\Http\Controllers
 */
class ClientController extends Controller
{

    private $services;

    /**
     * Create a new controller instance.
     *
     * @param ClientServices $services
     */
    public function __construct(ClientServices $services)
    {
        $this->middleware('auth');
        $this->services = $services;
    }

    public function index()
    {
        $results = $this->services->paginate();
        return view('layouts.pages.client.index', compact('results'));
    }

    public function create()
    {
        $states = State::orderBy('abbr')->get(['id', 'abbr']);
        return view('layouts.pages.client.create', compact('states'));
    }

    public function store(ClientRequest $request)
    {
        return $this->services->create($request->all());
    }

    public function edit($id)
    {
        $result = $this->services->show($id);
        $states = State::orderBy('abbr')->get(['id', 'abbr']);
        $cities = City::where('state_id', '=', $result['state_id'])->orderBy('name')->get(['id', 'name']);
        return view('layouts.pages.client.edit', compact('result', 'states', 'cities'));
    }

    public function update(ClientRequest $request, $id)
    {
        return $this->services->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->services->delete($id);
    }


}
