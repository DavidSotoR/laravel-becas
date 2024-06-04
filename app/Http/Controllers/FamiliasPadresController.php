<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\FamiliasPadres;

class FamiliasPadresController extends Controller
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

    public function lista($id_familia){
        $lista = FamiliasPadres::where('id_familia',$id_familia)->get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = FamiliasPadres::where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'id_familia' => 'required|int',
            'id_familias_padres_tipo' => 'required|int',
            'nombre' => 'required',
            'edad' => 'required|int',
            'vive' => 'required',
            'direccion' => 'required',
            'ocupacion_actual' => 'required|nullable',
            'empresa_trabajo' => 'required|nullable',
            'email' => 'string|nullable',
            'telefono_casa' => 'string|nullable',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $elemento = FamiliasPadres::
                    where('id_familia',$request->id_familia)
                    ->where('id_familias_padres_tipo',$request->id_familias_padres_tipo)
                    ->first();
        if($elemento){
            return response()->json(["id_familias_padres_tipo"=>["El tipo de familiar ya fu dado de alta con anterioridad"]], 400);
        }

        $cliente = FamiliasPadres::create($validator->validate());

        return response()->json(['message' => 'Nuevo registro de padre creado', 'data' => $cliente], 201);
    }
}
