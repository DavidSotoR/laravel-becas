<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CatalogoEncuestasPreguntasTipos;

class CatalogoEncuestasPreguntasTiposController extends Controller
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
        $lista = CatalogoEncuestasPreguntasTipos::get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = CatalogoEncuestasPreguntasTipos::where('id',$id)->first();
        return response()->json($elemento);
    }
}
