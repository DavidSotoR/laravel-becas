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

    public function listaEnProceso(Request $request){
        $query = ServicioEstados::query();
        $query->where('id','!=',1);
        $query->where('estado','=',0);
        $lista = $query->get();
        return response()->json($lista);
    }
}
