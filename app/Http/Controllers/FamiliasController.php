<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Familias;
use App\ServicioEstudio;

class FamiliasController extends Controller
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
        $query = Familias::query();

        //filtrat por siclo escolar id_ciclo_escolar
        /*if(isset($request->id_ciclo_escolar)){
            $query->where('id_ciclo_escolar', $request->id_ciclo_escolar);
        }*/

        $lista = $query->get();
        return response()->json($lista);
    }

    public function estudioSocioeconomico($id_familia = 0){
        //$id_familia = Auth::user()->id;
        //return $id_familia;
        $elemento = ServicioEstudio::with(['cliente','proyecto'])->where('id_familia',$id_familia)->first();
        return response()->json($elemento);
    }

    public function id($id){
        $elemento = Familias::where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'nombre' => 'required',
            //'id_ciclo_escolar' => 'required|int',
            'situacion_beca' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $cliente = Familias::create($validator->validate());

        return response()->json(['message' => 'Nuevo registro creado', 'data' => $cliente], 201);
    }
}
