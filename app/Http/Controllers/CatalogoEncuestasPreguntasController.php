<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\CatalogoEncuestasPreguntas;

class CatalogoEncuestasPreguntasController extends Controller
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

    public function id($id){
        //with('tipoCliente','preguntas')->
        $elemento = CatalogoEncuestasPreguntas::where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'pregunta'=>'required|string',
            'puntos_maximos'=>'required|int',
            'id_catalogo_encuesta' => 'required|int',
            'id_catalogo_encuestas_preguntas_tipo' => 'required|int',
            'id_catalogo_encuestas_preguntas_parametro_clasificacion' => 'required|int',
        ]);

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        $elemento = CatalogoEncuestasPreguntas::create(
            $validator->validate()
        );

        return response()->json(['message' => 'Nuevo cliente creado', 'data' => $elemento], 201);
    }

    public function editar(Request $request){
        $id = $request->id;

        $validator = Validator::make($request->all(),[
            'nombre' => ['required','string','min:2',Rule::unique('catalogo_encuestas')->ignore($id)],
            'descripcion' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = CatalogoEncuestas::where('id',$id)->first();
        $editar->nombre = $request->nombre;
        $editar->descripcion = $request->descripcion;
        $editar->save();


        return response()->json(['message' => 'Encuesta modificada', 'data' => $editar], 201);
    }
}
