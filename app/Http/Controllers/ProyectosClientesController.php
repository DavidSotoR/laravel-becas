<?php

namespace App\Http\Controllers;

use App\ProyectosClientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProyectosClientesController extends Controller
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

    public function lista($id_proyecto){
        $query = ProyectosClientes::query();

        $query->where('id_proyecto', $id_proyecto);

        $lista = $query->get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = ProyectosClientes::where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'id_cliente' => 'required|int',
            'id_proyecto' => 'required|int',
        ]);

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        $cliente = ProyectosClientes::create(array_merge(
            $validator->validate()
        ));

        return response()->json(['message' => 'Nuevo elemento creado', 'data' => $cliente], 201);
    }

    public function eliminar($id = 0){
        if(!$id){
            return response()->json(["id"=> ["El campo id es requerido."]], 400);
        }

        $eliminar = ProyectosClientes::where('id',$id)->delete();

        return response()->json(['message' => 'Elemento modificado', 'data' => $eliminar], 201);
    }
}
