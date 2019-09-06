<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotaFiscalEletronicaRequest;
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
        // $this->middleware('auth');
        $this->services = $services;
    }

    public function index()
    {
        return $this->services->gerarNota();
    }

}
