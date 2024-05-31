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

    public function lista(){
        $lista = User::get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = User::where('id',$id)->first();
        return response()->json($elemento);
    }
}
