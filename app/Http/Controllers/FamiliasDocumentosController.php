<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
//use Illuminate\Support\Facades\Storage;
use App\FamiliasDocumentos;

class FamiliasDocumentosController extends Controller
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
        $lista = FamiliasDocumentos::get();
        return response()->json($lista);
    }

    public function id($id){
        //return Storage::download('file.jpg', $name, $headers);
        $elemento = FamiliasDocumentos::where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){
        //Storage::disk('local')->put('example.txt', 'Contents');
        $validator = Validator::make($request->all(),[
            'id_familia' => 'required|int',
            'id_familias_documentos_tipo' => 'required|int',
            'id_ciclo_escolar' => 'required|int',

            'nombre' => 'required|nullable',
            'directorio' => 'required|nullable',
            'alias' => 'required|nullable',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $cliente = FamiliasDocumentos::create($validator->validate());

        return response()->json(['message' => 'Nuevo cliente creado', 'data' => $cliente], 201);
    }
    //Storage::delete('file.jpg');
    //Storage::delete(['file.jpg', 'file2.jpg']);
}
