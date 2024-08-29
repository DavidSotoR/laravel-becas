<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Perfiles;

class PerfilesController extends Controller
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
        $query = Perfiles::query();

        if(isset($request->tipos)){
            if($request->tipos == 'internos'){
                $query->where('interno', true);
            }
            if($request->tipos == 'externos'){
                $query->where('interno', false);
            }
        }

        $lista = $query->get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = Perfiles::where('id',$id)->first();
        return response()->json($elemento);
    }
}
