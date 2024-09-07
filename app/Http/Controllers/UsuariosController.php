<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\User;

class UsuariosController extends Controller
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
        $query = User::query()->with('perfil','cliente');

        if(isset($request->id_cliente)){
            if($request->id_cliente == 0){
                $query->where('id_cliente',null);
            }else{
                $query->where('id_cliente',$request->id_cliente);
            }
        }

        if(isset($request->tipos)){
            if($request->tipos == 'internos'){
                $query->whereHas('perfil', function ($query) {
                    $query->where('interno', true);
                });
            }
            if($request->tipos == 'externos'){
                $query->whereHas('perfil', function ($query) {
                    $query->where('interno', false);
                });
            }
        }

        $lista = $query->get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = User::with('perfil','cliente')->where('id',$id)->first();
        return response()->json($elemento);
    }

    public function editar(Request $request){
        $id = $request->id;
        $validator = Validator::make($request->all(),[
            'id' => 'required|int',
            'name' => ['required','min:2', Rule::unique('users')->ignore($id)],
            'email' => 'required',
            'id_perfil' => 'required|int',
            'id_cliente' => 'nullable|int',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = User::where('id',$id)->first();
        $editar->name = $request->name;
        $editar->email = $request->email;
        $editar->id_perfil = $request->id_perfil;
        $editar->id_cliente = $request->id_cliente;
        $editar->save();


        return response()->json(['message' => 'Usuario modificado', 'data' => $editar], 201);
    }
    public function disableOrEnable(Request $request){
        $id = $request->id;
        $editar = User::where('id',$id)->first();
        $editar->active = !$editar->active;
        $editar->save();
        
        return response()->json(['message' => 'Usuario modificado', 'data' => $editar], 201);
    }
}
