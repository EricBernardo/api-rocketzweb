<?php

namespace App\Http\Controllers;

use App\Services\NotaFiscalEletronicaServices;

/**
 * Class NotaFiscalEletronicaController
 * @package App\Http\Controllers
 */
class NotaFiscalEletronicaController extends Controller
{

    private $services;

    /**
     * Create a new controller instance.
     *
     * @param NotaFiscalEletronicaServices $services
     */
    public function __construct(NotaFiscalEletronicaServices $services)
    {
        $this->middleware('auth');
        $this->services = $services;
    }

    public function show($id)
    {
        return $this->services->show($id);
    }

    public function store($id)
    {
        return $this->services->store($id);
    }

    public function destroy($id)
    {
        return $this->services->delete($id);
    }

}
