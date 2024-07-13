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
            'nombre' => 'required|unique:clientes',
            'descripcion' => 'required',
            'notificaciones_email' => 'required',
            'id_tipo_cliente' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $cliente = Clientes::create($validator->validate());

        return response()->json(['message' => 'Nuevo cliente creado', 'data' => $cliente], 201);
    }

    public function editar(Request $request){
        $id = $request->id;
        $validator = Validator::make($request->all(),[
            'id' => 'required',
            'nombre' => ['required','min:2', Rule::unique('clientes')->ignore($id)],
            'descripcion' => 'required',
            'notificaciones_email' => 'required',
            'id_tipo_cliente' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = Clientes::where('id',$id)->first();
        $editar->nombre = $request->nombre;
        $editar->descripcion = $request->descripcion;
        $editar->notificaciones_email = $request->notificaciones_email;
        $editar->id_tipo_cliente = $request->id_tipo_cliente;
        if(isset($request->id_clientes_hermanos)){
            $editar->id_clientes_hermanos = $request->id_clientes_hermanos;
        }
        $editar->save();


        return response()->json(['message' => 'Cliente modificado', 'data' => $editar], 201);
    }
}
