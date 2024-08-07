<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\CatalogoEncuestasPreguntasParametrosClasificacionItems;

class CatalogoEncuestasPreguntasParametrosClasificacionItemsController extends Controller
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

    public function lista(Request $request,$id_parametros = null,$id_pregunta = null){

        $query = CatalogoEncuestasPreguntasParametrosClasificacionItems::query();

        //Filtrar encuestas por filtro de cliente empresa o escuelas
        if($id_parametros){
            $query->where('id_catalogo_encuestas_preguntas_parametro_clasificacion', $id_parametros);
        }
        if($id_pregunta){
            $query->where('id_catalogo_encuestas_preguntas_parametro_clasificacion', $id_pregunta);
        }

        $lista = $query->get();

        return response()->json($lista);
    }

    public function id($id){
        $elemento = CatalogoEncuestasPreguntasParametrosClasificacionItems::where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){
        $validator = Validator::make($request->all(),[
            'id_catalogo_encuestas_preguntas_parametro_clasificacion' => 'nullable|int',
            'id_catalogo_encuestas_preguntas' => 'nullable|int',
            'texto' => 'nullable|string',
            'limite_superior' => 'required|int',
            'limiten_inferior' => 'required|int',
            'valor' =>'required|string',
        ]);

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        $elemento = CatalogoEncuestasPreguntasParametrosClasificacionItems::create(
            $validator->validate()
        );

        return response()->json(['message' => 'Elemento Guardado', 'data' => $elemento], 201);
    }

    public function editar(Request $request,$id = 0){
        if(!$id){
            return response()->json(["id" => ["id de ítem es requerido"]], 400);
        }

        $validator = Validator::make($request->all(),[
            'id_catalogo_encuestas_preguntas_parametro_clasificacion' => 'nullable|int',
            'id_catalogo_encuestas_preguntas' => 'nullable|int',
            'texto' => 'nullable|string',
            'limite_superior' => 'required|int',
            'limiten_inferior' => 'required|int',
            'valor' =>'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = CatalogoEncuestasPreguntasParametrosClasificacionItems::where('id',$id)->first();

        $editar->id_catalogo_encuestas_preguntas_parametro_clasificacion = $request->id_catalogo_encuestas_preguntas_parametro_clasificacion;
        $editar->id_catalogo_encuestas_preguntas = $request->id_catalogo_encuestas_preguntas;
        $editar->texto = $request->texto;
        $editar->limite_superior = $request->limite_superior;
        $editar->limiten_inferior = $request->limiten_inferior;
        $editar->valor = $request->valor;
        $editar->save();


        return response()->json(['message' => 'Encuesta modificada', 'data' => $editar], 201);
    }


    public function eliminar($id){
        $elemento = CatalogoEncuestasPreguntasParametrosClasificacionItems::where('id',$id)->delete();
        return response()->json($elemento);
    }
}
