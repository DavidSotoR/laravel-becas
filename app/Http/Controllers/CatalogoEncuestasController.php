<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\CatalogoEncuestas;

class CatalogoEncuestasController extends Controller
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
        $query = CatalogoEncuestas::query();
        $query->with('tipoCliente');

        //Filtrar encuestas por filtro de cliente empresa o escuelas
        if($request->id_tipo_cliente){
            $query->where('id_tipo_cliente', $request->id_tipo_cliente);
        }

        $lista = $query->get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = CatalogoEncuestas::with('tipoCliente','preguntas')->where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'id_tipo_cliente' => 'required|int',
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        $elemento = CatalogoEncuestas::create(
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


    public function copia(Request $request, $id){

        $elemento = CatalogoEncuestas::with('preguntas','parametros','parametros.items')->where('id',$id)->first();
        if(isset($request->nombre) && $elemento){
            $request->nombre = $elemento->nombre." - ".$request->nombre;
        }

        //return response()->json($elemento);

        $validator = Validator::make($request->all(),[
            'nombre' => ['required','string','min:2',Rule::unique('catalogo_encuestas')->ignore($id)],
            'password' => 'required'
        ]);

        /*$UserPassword = auth()->user()->password;
        $ComprovacionPassword = bcrypt($request->password);

        if($UserPassword != $ComprovacionPassword){
            return response()->json(['error' => ['password'=>['Contraseña Incorrecta',$UserPassword,$ComprovacionPassword]]], 400);
        }*/


        $nuevoElemento = $elemento->replicate();
        $nuevoElemento->nombre = $request->nombre;
        $nuevoElemento->save();

        $idsParametrosTransacction = array();
        $idsPreguntasTransacction = array();

        // Clonar parámetros
        foreach ($elemento->parametros as $parametro) {
            $nuevoParametro = $parametro->replicate();
            $nuevoElemento->parametros()->save($nuevoParametro);

            // Clonar items
            foreach ($parametro->items as $item) {
                $nuevoItem = $item->replicate();
                $nuevoParametro->items()->save($nuevoItem);
            }

            $idsPreguntasTransacction[$parametro->id] = $nuevoElemento->id;
        }

        // Clonar preguntas
        foreach ($elemento->preguntas as $pregunta) {
            //$pregunta->
            $nuevaPregunta = $pregunta->replicate();

            if($nuevaPregunta->id_catalogo_encuestas_preguntas_parametro_clasificacion !== null){
                $nuevaPregunta->id_catalogo_encuestas_preguntas_parametro_clasificacion = $idsPreguntasTransacction[$nuevaPregunta->id_catalogo_encuestas_preguntas_parametro_clasificacion];
            }
            if($nuevaPregunta->id_parametro_clasificacion_parametro_adicional_uno !== null){
                $nuevaPregunta->id_parametro_clasificacion_parametro_adicional_uno = $idsPreguntasTransacction[$nuevaPregunta->id_parametro_clasificacion_parametro_adicional_uno];
            }
            if($nuevaPregunta->id_parametro_clasificacion_parametro_adicional_dos !== null){
                $nuevaPregunta->id_parametro_clasificacion_parametro_adicional_dos = $idsPreguntasTransacction[$nuevaPregunta->id_parametro_clasificacion_parametro_adicional_dos];
            }

            $nuevoElemento->preguntas()->save($nuevaPregunta);

            /*'id_catalogo_encuestas_preguntas_parametro_clasificacion',
            'id_parametro_clasificacion_parametro_adicional_uno',
            'id_parametro_clasificacion_parametro_adicional_dos',
            $idsPreguntasTransacction[$nuevaPregunta->id] = array(
                'parametro' => ,
                'parametro_adicional_uno'=> ,
                'parametro_adicional_dos' =>
            );*/
        }


        return response()->json($elemento);
    }
}
