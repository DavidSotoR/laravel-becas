<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\CicloEscolar;

class CicloEscolarController extends Controller
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
        $lista = CicloEscolar::get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = CicloEscolar::where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'inicio' => 'required|date_format:Y/m/d',
            'fin' => 'required|date_format:Y/m/d',
        ]);

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        $cliente = CicloEscolar::create(array_merge(
            $validator->validate()
        ));

        return response()->json(['message' => 'Nuevo cliente creado', 'data' => $cliente], 201);
    }
}
