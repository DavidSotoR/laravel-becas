<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\CatalogoEncuestasPreguntas;
use App\CatalogoEncuestasPreguntasParametrosClasificacionItems;

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

    public function lista($id_encuesta){
        $query = CatalogoEncuestasPreguntas::query();
        //$query->with(['catalogoEncuesta','tipoPreguntas','clasificacionParametro']);
        $query->with(['tipoPreguntas','clasificacionParametro']);

        $query->where('id_catalogo_encuesta', $id_encuesta);


        $lista = $query->get();
        return response()->json($lista);
    }

    public function id($id){
        //with('tipoCliente','preguntas')->
        //with(['catalogoEncuesta','tipoPreguntas','clasificacionParametro'])->
        $elemento = CatalogoEncuestasPreguntas::with(['catalogoEncuesta','tipoPreguntas','clasificacionParametro','items'])->where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'pregunta'=>'required|string',
            'id_catalogo_encuesta' => 'required|int',
            'id_catalogo_encuestas_preguntas_tipo' => 'required|int',
            'id_catalogo_encuestas_preguntas_parametro_clasificacion' => 'nullable|int',
            'orden'=> 'nullable|int',
            'numero_pregunta'=> 'nullable|int',
            'longitud_respuesta'=> 'nullable|int',
        ]);

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        $elemento = CatalogoEncuestasPreguntas::create(
            $validator->validate()
        );

        return response()->json(['message' => 'Nuevo cliente creado', 'data' => $elemento], 201);
    }


    public function setTipoParametro(Request $request){

        $validator = Validator::make($request->all(),[
            'id'=>'required|int',
            'id_parametro_clasificacion_tipo' => 'required|int',
        ]);

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        $editar = CatalogoEncuestasPreguntas::where('id',$request->id)->first();
        $editar->id_parametro_clasificacion_tipo = $request->id_parametro_clasificacion_tipo;
        $editar->save();

        return response()->json(['message' => 'Elemento Actualizado', 'data' => $editar], 201);
    }

    public function editar(Request $request,$id_encuesta = 0,$id = 0){

        if(!$id_encuesta){
            return response()->json(["id_encuesta" => ["id_encuesta es requerido"]], 400);
        }

        if(!$id){
            return response()->json(["id" => ["id de pregunta es requerido"]], 400);
        }

        $validator = Validator::make($request->all(),[
            'pregunta'=> ['required','string','min:2',
                            Rule::unique('catalogo_encuestas_preguntas')
                            ->where(function ($query) use ($id_encuesta) {
                                return $query->where('id_catalogo_encuesta', $id_encuesta);
                            })
                            ->ignore($id)
                          ],
            'puntos_maximos'=>'required|int',
            'id_catalogo_encuestas_preguntas_tipo' => 'required|int',
            'id_catalogo_encuestas_preguntas_parametro_clasificacion' => 'nullable|int',
            'orden'=> 'nullable|int',
            'numero_pregunta'=> 'nullable|int',
            'longitud_respuesta'=> 'nullable|int',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = CatalogoEncuestasPreguntas::where('id',$id)->first();
        $editar->pregunta = $request->pregunta;
        $editar->puntos_maximos = $request->puntos_maximos;
        $editar->id_catalogo_encuestas_preguntas_tipo = $request->id_catalogo_encuestas_preguntas_tipo;
        $editar->id_catalogo_encuestas_preguntas_parametro_clasificacion = $request->id_catalogo_encuestas_preguntas_parametro_clasificacion;

        if(isset($request->orden))
            $editar->orden = $request->orden;

        $editar->numero_pregunta = $request->numero_pregunta;

        if(isset($request->longitud_respuesta))
            $editar->longitud_respuesta = $request->longitud_respuesta;

        $editar->save();


        return response()->json(['message' => 'Encuesta modificada', 'data' => $editar], 201);
    }


    public function parametros(Request $request, $id_pregunta){
        $id_parametro = CatalogoEncuestasPreguntas::where('id',$id_pregunta)
            ->first()->id_catalogo_encuestas_preguntas_parametro_clasificacion;
        if(!$id_parametro){
            return response()->json([]);
        }
        $query = CatalogoEncuestasPreguntasParametrosClasificacionItems::query();
        $query->where('id_catalogo_encuestas_preguntas_parametro_clasificacion',$id_parametro);

        if(isset($request->id_catalogo_pregunta)){
            $query->where('id_catalogo_encuestas_preguntas', $request->id_catalogo_pregunta);
        }

        $elementos = $query->get();

        return response()->json($elementos);
    }
}
