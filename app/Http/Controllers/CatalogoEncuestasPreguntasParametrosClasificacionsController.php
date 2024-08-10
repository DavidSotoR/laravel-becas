<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\CatalogoEncuestasPreguntasParametrosClasificacions;

class CatalogoEncuestasPreguntasParametrosClasificacionsController extends Controller
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

    public function lista($id_catalogo_encuesta){
        $lista = CatalogoEncuestasPreguntasParametrosClasificacions::with('tipoParametro')->where('id_catalogo_encuesta',$id_catalogo_encuesta)->get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = CatalogoEncuestasPreguntasParametrosClasificacions::with('tipoParametro','items','porPregunta','porPregunta.tipoPreguntas')->where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){
        $id_catalogo_encuesta = $request->id_catalogo_encuesta;

        $validator = Validator::make($request->all(),[
            'id_catalogo_encuesta' => 'required|int',
            'nombre'=>[
                        'required','string','min:2',
                        Rule::unique('catalogo_encuestas_preguntas_parametros_clasificaciones')
                        ->where(function ($query) use($id_catalogo_encuesta) {
                            return $query->where('id_catalogo_encuesta', $id_catalogo_encuesta);
                        }),
                    ],
            'puntos_maximo' => 'required|int',
            'id_catalogo_encuestas_preguntas_parametros_clasificaciones_tipos' =>'required|int',
        ]);

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        $elemento = CatalogoEncuestasPreguntasParametrosClasificacions::create(
            $validator->validate()
        );

        return response()->json(['message' => 'Elemento Guardado', 'data' => $elemento], 201);
    }

    public function editar(Request $request){
        $id = $request->id;
        $id_catalogo_encuesta = $request->id_catalogo_encuesta;

        $validator = Validator::make($request->all(),[
            'nombre' => [
                            'required','string','min:2',
                            Rule::unique('catalogo_encuestas_preguntas_parametros_clasificaciones')
                            ->where(function ($query) use($id,$id_catalogo_encuesta) {
                                return $query->where('id', $id)
                                ->where('id_catalogo_encuesta', $id_catalogo_encuesta);
                            }),
                        ],
            'id_catalogo_encuesta' => 'required|int',
            'puntos_maximo' => 'required|int',
            'id_catalogo_encuestas_preguntas_parametros_clasificaciones_tipos' =>'required|int',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = CatalogoEncuestasPreguntasParametrosClasificacions::where('id',$id)->first();
        $editar->nombre = $request->nombre;
        $editar->id_catalogo_encuesta = $request->id_catalogo_encuesta;
        $editar->puntos_maximo = $request->puntos_maximo;
        $editar->save();


        return response()->json(['message' => 'Encuesta modificada', 'data' => $editar], 201);
    }
}
