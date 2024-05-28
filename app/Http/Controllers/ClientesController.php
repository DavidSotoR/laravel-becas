<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Clientes;

class ClientesController extends Controller
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

    public function lista(){
        $lista = Clientes::get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = Clientes::where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'nombre' => 'required',
            'id_tipo_cliente' => 'required|int',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $cliente = Clientes::create(array_merge(
            $validator->validate()
        ));

        return response()->json(['message' => 'Nuevo cliente creado', 'cliente' => $cliente], 201);
    }

    public function editar(Request $request){

        $validator = Validator::make($request->all(),[
            'id' => 'required',
            'nombre' => 'required',
            'id_tipo_cliente' => 'required|int',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = new Perfiles;
        $editar->id = $request->id;
        $editar->nombre = $request->nombre;
        $editar->descripcion = $request->descripcion;
        $editar->notificaciones_email = $request->notificaciones_email;
        $editar->id_tipo_cliente = $request->id_tipo_cliente;


        return response()->json(['message' => 'Nuevo cliente creado', 'cliente' => $editar], 201);
    }
}
