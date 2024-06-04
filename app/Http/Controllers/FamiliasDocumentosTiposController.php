<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\FamiliasDocumentosTipos;

class FamiliasDocumentosTiposController extends Controller
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
        $lista = FamiliasDocumentosTipos::get();
        return response()->json($lista);
    }
}
