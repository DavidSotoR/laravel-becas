<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Alumnos;

class AlumnosController extends Controller
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
        $lista = Alumnos::where('id_familias',$id_familia)->get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = Alumnos::where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'id_familias' => 'required|int',
            'id_ciclo_escolar' => 'required|int',

            'nombre' => 'required',
            'domicilio' => 'required',
            'colonia' => 'required',
            'municipio' => 'required',
            'codigo_postal' => 'required',

            'telefono_madre' => 'string|nullable',
            'telefono_padre' => 'string|nullable',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $cliente = Alumnos::create($validator->validate());

        return response()->json(['message' => 'Nuevo cliente creado', 'data' => $cliente], 201);
    }
}
