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

        //Filtrar encuestas por filtro de cliente empresa o escuelas
        if($request->id_tipo_cliente){
            $query->where('id_tipo_cliente', $request->id_tipo_cliente);
        }

        $lista = $query->get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = CatalogoEncuestas::where('id',$id)->first();
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
}
