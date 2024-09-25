<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
//use Illuminate\Support\Facades\Storage;
use App\FamiliasDocumentos;
use App\FamiliasDocumentosTipos;
use App\ServicioEstados;
use App\ServicioEstudio;
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
        $validator = Validator::make($request->all(), [
            'id_familia' => 'required|int|exists:servicios_estudios,id_familia',
            'id_familias_documentos_tipo' => 'required|int',
            'id_servicio_estudio' => 'required|int|exists:servicios_estudios,id',
            'files.*' => 'required' // Validación para archivos múltiples
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        // Obtener los detalles del estudio y del tipo de documento
        $idse = ServicioEstudio::where('id_familia', '=', auth()->id())->first();
        $idEstudio = FamiliasDocumentosTipos::where('id', '=', $request->id_familias_documentos_tipo)->first();
    
        // Verificar que los archivos existan en la solicitud
        if ($request->hasFile('files')) {
            // Recorrer y procesar cada archivo
            foreach ($request->file('files') as $file) {
                // Obtener el nombre original y el alias para cada archivo
                $documentoName = $file->getClientOriginalName();
                $documentoAlias = time() . '_' . $file->getClientOriginalName();
    
                // Definir la carpeta donde se guardarán los archivos
                $nombreEstudio = strtoupper(str_replace(' ', '_', $idEstudio['nombre']));
                $carpeta_guardar = $idse['directorio'] . $nombreEstudio;
    
                // Almacenar el archivo en la carpeta especificada en el disco 'public'
                $documentoPath = $file->storeAs($carpeta_guardar, $documentoAlias, 'public');
    
                // Crear el registro en la base de datos
                $elemento = FamiliasDocumentos::create([
                    "id_familia" => $request->id_familia,
                    "id_familias_documentos_tipo" => $request->id_familias_documentos_tipo,
                    "id_servicio_estudio" => $request->id_servicio_estudio,
                    'nombre' => $documentoName,
                    'directorio' => $documentoPath,
                    'alias' => $documentoAlias
                ]);
            }
    
            // Retornar la respuesta exitosa
            return response()->json(['message' => 'Archivos subidos y guardados correctamente'], 201);
        }
    
        return response()->json(['message' => 'No se encontraron archivos para subir'], 400);


    }
    //Storage::delete('file.jpg');
    //Storage::delete(['file.jpg', 'file2.jpg']);
}
