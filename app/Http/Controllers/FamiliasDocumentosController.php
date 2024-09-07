<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
//use Illuminate\Support\Facades\Storage;
use App\FamiliasDocumentos;
use File;

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

    public function lista($id_familia){
        //$lista = FamiliasDocumentos::where('id_familia',$id_familia)->get();
        //return response()->json($lista);

        $query = FamiliasDocumentos::query();
        $query->where('id_familia',$id_familia);

        if(isset($request->id_servicio_estudio)){
            $query->where('id_servicio_estudio', $request->id_servicio_estudio);
        }

        $lista = $query->get();

        return response()->json($lista);
    }

    public function id($id){
        //return Storage::download('file.jpg', $name, $headers);
        $elemento = FamiliasDocumentos::where('id',$id)->first();
        return response()->json($elemento);
    }

    public function file($alias){
        $elemento = FamiliasDocumentos::where('alias',$alias)->first();

        //return storage_path('app/public/' . $elemento->directorio);
        return response()->download(storage_path('app/public/' . $elemento->directorio));
    }

    public function nuevo(Request $request){
        //Storage::disk('local')->put('example.txt', 'Contents');

        $validator = Validator::make($request->all(),[
            'id_familia' => 'required|int',
            'id_familias_documentos_tipo' => 'required|int',
            'id_servicio_estudio' => 'required|int',
            'file' => 'required|mimes:csv,txt,xlx,xls,pdf,png|max:2048',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }


        $documento = new File;

        $documentoName = $request->file->getClientOriginalName();
        $documentoAlias = time().'_'.$request->file->getClientOriginalName();
        $documentoPath = $request->file('file')->storeAs('uploads', $documentoAlias, 'public');


        $elemento = FamiliasDocumentos::create([
            "id_familia" => $request->id_familia,
            "id_familias_documentos_tipo" => $request->id_familias_documentos_tipo,
            "id_servicio_estudio" => $request->id_servicio_estudio,
            'nombre' => $documentoName,
            'directorio' => $documentoPath,
            'alias' => $documentoAlias
        ]);

        return response()->json(['message' => 'Nuevo documento añadidio', 'data' => $elemento], 201);

    }
    //Storage::delete('file.jpg');
    //Storage::delete(['file.jpg', 'file2.jpg']);
}
