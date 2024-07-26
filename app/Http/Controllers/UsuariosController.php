<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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

        $lista = $query->get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = User::with('perfil','cliente')->where('id',$id)->first();
        return response()->json($elemento);
    }
}
