<?php

namespace App\Http\Controllers;

use App\ServicioEstados;
use Illuminate\Http\Request;

class ServicioEstadosController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
}
