<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CatalogoEncuestasPreguntasParametrosClasificacionsTipo;

class CatalogoEncuestasPreguntasParametrosClasificacionsTipoController extends Controller
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

    public function lista(Request $request){
        $lista = CatalogoEncuestasPreguntasParametrosClasificacionsTipo::get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = CatalogoEncuestasPreguntasParametrosClasificacionsTipo::where('id',$id)->first();
        return response()->json($elemento);
    }
}
