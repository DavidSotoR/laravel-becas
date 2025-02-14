<?php

namespace App\Http\Controllers;

use App\CatalogoEncuestasRangos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CatalogoEncuestasRangosController extends Controller
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

    public function lista($id_encuesta){
        $lista = CatalogoEncuestasRangos::where('id_catalogo_encuesta',$id_encuesta)->get();
        return response()->json($lista);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_catalogo_encuesta' => 'required|int',
            'limite_superior' => 'required|int',
            'limite_inferior' => 'required|int',
            'porcentaje_sujerido' => 'required|int',
        ]);

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        $elemento = CatalogoEncuestasRangos::create(
            $validator->validate()
        );

        return response()->json(['message' => 'Elemento gardado', 'data' => $elemento], 201);
    }

    public function editar(Request $request){

        $validator = Validator::make($request->all(),[
            'id' => 'required|int',
            'limite_superior' => 'required|int',
            'limite_inferior' => 'required|int',
            'porcentaje_sujerido' => 'required|int',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = CatalogoEncuestasRangos::where('id',$request->id)->first();
        $editar->limite_superior = $request->limite_superior;
        $editar->limite_inferior = $request->limite_inferior;
        $editar->porcentaje_sujerido = $request->porcentaje_sujerido;
        $editar->save();

        return response()->json(['message' => 'Elemento modificado', 'data' => $editar], 201);
    }

    public function eliminar($id){
        $elemento = CatalogoEncuestasRangos::where('id',$id)->delete();
        return response()->json($elemento);
    }
}
