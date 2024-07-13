<?php

namespace App\Http\Controllers;

use App\ClientesHermanos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ClientesHermanosController extends Controller
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
        $lista = ClientesHermanos::with('lista')->get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = ClientesHermanos::with('lista')->where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'nombre' => 'required|unique:clientes_hermanos',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $cliente = ClientesHermanos::create($validator->validate());

        return response()->json(['message' => 'Nuevo elemento creado', 'data' => $cliente], 201);
    }

    public function editar(Request $request){
        $id = $request->id;
        $validator = Validator::make($request->all(),[
            'id' => 'required',
            'nombre' => ['required','min:2', Rule::unique('clientes_hermanos')->ignore($id)],
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = ClientesHermanos::where('id',$id)->first();
        $editar->nombre = $request->nombre;
        $editar->save();


        return response()->json(['message' => 'Elemento modificado', 'data' => $editar], 201);
    }

}
