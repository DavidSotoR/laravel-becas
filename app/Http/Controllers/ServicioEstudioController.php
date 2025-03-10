<?php

namespace App\Http\Controllers;

use App\ServicioEstudio;
use App\ServiciosEstudiosClientesComunes;
use App\Proyectos;
use App\OrdenesServicio;
use App\FamiliasPadres;
use App\User;
use App\ProyectosClientes;
use App\CatalogoEncuestas;
use App\CatalogoEncuestasPreguntasParametrosClasificacions;
use App\ServiciosEstudiosRespuestas;
use App\CatalogoEncuestasPreguntas;
use App\CatalogoEncuestasPreguntasParametrosClasificacionItems;
use App\FamiliasDocumentosTipos;
use App\FamiliasDocumentos;
use App\ServiciosEstudiosRespuestasClasificacion;
use App\Mail\NotificacionCorreo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use PDF;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;

use ZipArchive;

class ServicioEstudioController extends Controller
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

    public function lista(Request $request)
    {
        $query = ServicioEstudio::query()->with([
            'estado',
            'cliente',
            'proyecto',
            'ordenServicio',
            'colaborador',
            'padre',
            'madre',
            'contactoPrincipal',
        ]);

        if (isset($request->id_proyecto)) {

            $query->where('id_proyecto', $request->id_proyecto);

            if (isset($request->id_cliente)) {
                if ($request->id_cliente)
                    $query->where('id_cliente', $request->id_cliente);

                if (isset($request->id_orden_servicio)) {
                    if ($request->id_orden_servicio)
                        $query->where('id_orden_servicio', $request->id_orden_servicio);
                }
            }
        }

        if (isset($request->id_colaborador)) {
            if ($request->id_colaborador)
                $query->where('id_colaborador', $request->id_colaborador);
        }

        $lista = $query->get();
        return response()->json($lista);
    }

    public function listaEnProceso(Request $request)
    {
        $query = ServicioEstudio::query()->with([
            'estado',
            'cliente',
            'proyecto',
            'ordenServicio',
            'colaborador',
            'padre',
            'madre',
            'contactoPrincipal',
        ]);

        $user = auth()->user();
        $id_perfil = $user->perfil->id;
        //$query->where('id_colaborador',$user->id);
        switch ($id_perfil) {
            case 1:
                //Administrador
                $query->where('id_colaborador', $user->id);
                break;
            case 2:
                //Gerencia
                $query->where('id_colaborador', $user->id);
                break;
            case 3:
                //Calidad
                $query->where('id_colaborador', $user->id);
                break;
            case 4:
                //Colaboradores
                $query->where('id_colaborador', $user->id);
                break;
            case 5:
                //Empresas
                return response()->json([]);
                break;
            case 6:
                //Familias
                return response()->json([]);
                break;
            default:
                return response()->json([]);
        }

        if (isset($request->id_proyecto)) {

            $query->where('id_proyecto', $request->id_proyecto);

            if (isset($request->id_cliente)) {
                if ($request->id_cliente)
                    $query->where('id_cliente', $request->id_cliente);

                if (isset($request->id_orden_servicio)) {
                    if ($request->id_orden_servicio)
                        $query->where('id_orden_servicio', $request->id_orden_servicio);
                }
            }
        }
        $query->where('id_servicio_estado', '!=', 1);
        if (isset($request->id_servicio_estado)) {
            $query->where('id_servicio_estado', $request->id_servicio_estado);
        }

        $lista = $query->get();
        return response()->json($lista);
    }

    private function preguntaEsporHijo($id_proyecto,$id_cliente){

        $pregunta = ProyectosClientes::with(
                            [
                                'encuesta.preguntas' => function ($query) { $query ->where('id_catalogo_encuestas_preguntas_tipo', 4);}
                            ]
                        )
                ->where('id_proyecto', $id_proyecto)
                ->where('id_cliente', $id_cliente)
                ->first()->encuesta->preguntas;

         return $pregunta;
    }
    public function listaConcluidos(Request $request, int $id_proyecto)
    {

        $user = auth()->user();
        $id_perfil = $user->perfil->id;

        $query = ServicioEstudio::query()->with([
            'estado',
            'cliente',
            'proyecto',
            'ordenServicio',
            'colaborador',
            'padre',
            'madre',
            'contactoPrincipal',
        ])->orderBy('candidato', 'asc');

        //$query->where('id_colaborador',$user->id);
        switch ($id_perfil) {
                //Administrador
            case 1:
                //Gerencia
            case 2:
                //Calidad
            case 3:
                //Colaboradores
            case 4:

                if (isset($request->id_cliente)) {
                    if ($request->id_cliente)
                        $query->where('id_cliente', $request->id_cliente);
                }

                $query->where('id_colaborador', $user->id);


                $query->where('id_servicio_estado', '!=', 1);
                if (isset($request->id_servicio_estado)) {
                    $query->where('id_servicio_estado', $request->id_servicio_estado);
                }

                break;
            case 5:
                //Empresas
                $id_cliente = $user->id_cliente;
                $query->where('id_cliente', $id_cliente);

                $query->where('id_servicio_estado', '!=', 1);

                break;
            case 6:
                //Familias
                return response()->json([]);
                break;
            default:
                return response()->json([]);
        }



        $query->where('id_proyecto', $id_proyecto);


        if (isset($request->id_orden_servicio)) {
            if ($request->id_orden_servicio)
                $query->where('id_orden_servicio', $request->id_orden_servicio);
        }

        $lista = $query->get();

        /*$proyectoCliente = ProyectosClientes::
                            with(['encuesta','encuesta.preguntas','proyecto'])
                            ->where('id_proyecto',$id_proyecto)
                            ->where('id_cliente',$user->id_cliente)
                            ->first();
        $encuesta = $proyectoCliente->encuesta;*/
        $distribucion_del_gasto = false;
        if(isset($request->distribucion_del_gasto)){
            $distribucion_del_gasto = true;
        }

        $encuesta_por_hijo = false;
        if(isset($lista[0])){

            $peimer_estudio = $lista[0];
            $pregunta = $this->preguntaEsporHijo($peimer_estudio->id_proyecto,$peimer_estudio->id_cliente);
            if(count($pregunta)){
                $encuesta_por_hijo = true;
            }

        }

        if($encuesta_por_hijo){
            $estudios_por_hijo = array();

            foreach ($lista as $estudio) {
                /*$parametros = CatalogoEncuestasPreguntasParametrosClasificacions::where('id_catalogo_encuesta',$encuesta->id)->get();

                foreach($parametros AS &$parametro){
                    $parametro['puntos'] = $this -> puntosPrecuntaSeccion($parametro,$estudio->id,$encuesta->preguntas);
                }
                */

                //$parametros = $this->estudioParametrosPuntos($estudio->id);
                //$estudio['parametros'] = $parametros;

                //CatalogoEncuestasPreguntas::where()->first();
                $pregunta_get = $this->preguntaEsporHijo($estudio->id_proyecto,$estudio->id_cliente);
                $pregunta = $pregunta_get[0];
                $lista_de_hijos = ServiciosEstudiosRespuestas::
                    where('id_servicio_estudio',$estudio->id)
                    ->where('id_catalogo_encuestas_pregunta',$pregunta->id)
                    ->where('nombre', '!=', '')
                    ->get();
                foreach($lista_de_hijos AS $key => $hijo){
                    $estudio_hijo = array();
                    $estudio_hijo = clone $estudio;
                    $parametros = $this->estudioParametrosPuntos($estudio->id,$hijo['id']);
                    $estudio_hijo['parametros'] = $parametros;
                    $estudio_hijo['nombre_hijo'] =  $hijo['nombre'];
                    //$estudio['hijos'] = $lista_de_hijos;
                    $estudio_hijo['hijo'] = $hijo;
                    $estudio_hijo['no_hijo'] = $key+1;

                    if($distribucion_del_gasto){
                        $estudio_hijo['distribucion_del_gasto'] = $this->getDistribucionDelGasto(
                            $estudio_hijo->id_proyecto,
                            $estudio_hijo->id_cliente,
                            $estudio_hijo->id
                        );
                    }

                    $estudios_por_hijo[] = $estudio_hijo;
                }
            }

            return response()->json($estudios_por_hijo);

        }else{
            foreach ($lista as &$estudio) {
                /*$parametros = CatalogoEncuestasPreguntasParametrosClasificacions::where('id_catalogo_encuesta',$encuesta->id)->get();

                foreach($parametros AS &$parametro){
                    $parametro['puntos'] = $this -> puntosPrecuntaSeccion($parametro,$estudio->id,$encuesta->preguntas);
                }
                */
                $parametros = $this->estudioParametrosPuntos($estudio->id,0);
                $estudio['parametros'] = $parametros;

                if($distribucion_del_gasto){
                    $estudio['distribucion_del_gasto'] = $this->getDistribucionDelGasto(
                        $estudio->id_proyecto,
                        $estudio->id_cliente,
                        $estudio->id
                    );
                }
            }


            return response()->json($lista);
        }
    }

    private function getDistribucionDelGasto($id_proyecto,$id_cliente,$id_estudio){

        $lista_totales = array();
        $id_encuesta = ProyectosClientes::where('id_proyecto',$id_proyecto)->where('id_cliente',$id_cliente)->first()->id_encuesta;
        $id_pregunta = CatalogoEncuestasPreguntas::where('id_catalogo_encuesta',$id_encuesta)->where('id_catalogo_encuestas_preguntas_tipo',15)->first()->id;

        if(!$id_pregunta){
            return $lista_totales;
        }

        $lista_respuestas = ServiciosEstudiosRespuestas::where('id_servicio_estudio',$id_estudio)->where('id_catalogo_encuestas_pregunta',$id_pregunta)->get();

        $lista_clasificacion = ServiciosEstudiosRespuestasClasificacion::get();


        foreach($lista_clasificacion as $clasificacion){
            $sumatoria = 0;
            foreach($lista_respuestas as $respuesta){
                if($respuesta->id_respuestas_clasificacions == $clasificacion->id){
                    $sumatoria += $respuesta->padre_monto;
                }
            }
            $lista_totales[] = [ "categoria" => $clasificacion->nombre, "total" => $sumatoria ] ;
        }

        return $lista_totales;
    }

    public function id($id)
    {
        /* $elemento = ServicioEstudio::with([
            'estado',
            'cliente',
            'proyecto',
            'ordenServicio',
            'colaborador',
            'familia',
            'padre',
            'madre',
            'colegiosComunes',
            'contactoPrincipal',
        ])->where('id', $id)->first(); */
        $elemento = ServicioEstudio::select([
            'id',
            DB::raw('estado as estado_columna'), // Alias para la columna estado
            'id_servicio_estado',
            'id_proyecto',
            'id_cliente',
            'id_familia',
            'id_orden_servicio',
            'id_colaborador',
            'id_calidad',
            'id_gerencia',
            'es_cliente_comun',
            'candidato',
            'situacion',
            'email',
            'telefono_movil',
            'telefono_contacto',
            'curp',
            'domicilio',
            'entrecalles',
            'departamento',
            'anterior_empleo',
            'anterior_puesto',
            'anterior_empresa',
            'anterior_antiguedad',
            'directorio',
            'direccion',
            'latitud',
            'longitud',
            'calle',
            'numero_exterior',
            'colonia',
            'municipio',
            'codigo_postal',
            'pais',
            'visita_fecha',
            'visita_hora',
            'visita_recordatorio',
            'porcentaje_otorgado',
            'clave_familia_colegio',
        ])
        ->with([
            'estado', // Esto trae la relación llamada "estado"
            'cliente',
            'proyecto',
            'ordenServicio',
            'colaborador',
            'familia',
            'padre',
            'madre',
            'colegiosComunes',
            'contactoPrincipal',
        ])
        ->where('id', $id)
        ->first();

        if (!$elemento) {
            return response()->json(["errors" => ["id" => ["Encuesta no exsiste"]]], 400);
        }

        $proyectoCliente = ProyectosClientes::with('encuesta')->where('id_proyecto', $elemento->id_proyecto)->where('id_cliente', $elemento->id_cliente)->first();

        //$elemento['proyecto_cliente'] = $proyectoCliente;
        $encuesta = $proyectoCliente->encuesta;

        $elemento['encuesta'] = $encuesta;

        return response()->json($elemento);
    }

    public function nuevo(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id_servicio_estado' => 'required|exists:servicio_estados,id',
            'id_proyecto' => 'required|exists:proyectos,id',
            'id_cliente' => 'required|exists:clientes,id',
            'id_orden_servicio' => 'required|exists:ordenes_servicio,id',
            'id_colaborador' => 'nullable|exists:users,id',
            'es_familia_comun' => 'nullable|boolean',
            'candidato' => 'required|string',
            'situacion' => 'nullable|string',
        ]);
        //FamiliasPadres

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }

        $elemento = ServicioEstudio::create($validator->validate());

        $servicio_estudio = $elemento->id;
        $colegios_comunes = $request->colegios_comunes;

        if (isset($colegios_comunes) and isset($servicio_estudio)) {
            foreach ($colegios_comunes as $id_colegio_comun) {
                ServiciosEstudiosClientesComunes::create(['id_servicio_estudio' => $servicio_estudio, 'id_cliente' => $id_colegio_comun]);
            }
        }

        return response()->json(['message' => 'Nuevo elemento creado', 'data' => $elemento], 201);
    }

    public function aniadirObservacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|int',
            'observaciones' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }

        $elemento = ServicioEstudio::find($request->id);

        $elemento->observaciones = $request->observaciones;
        $elemento->save();

        return response()->json(['message' => 'Nuevo elemento creado', 'data' => $elemento], 201);
    }

    public function editarFamiliaEstudioServicio(Request $request){
        $datos = $request->all();
        $padreUpdate = $datos['padre'];
        $madreUpdate = $datos['madre'];
        $seUpdate = ServicioEstudio::find($datos['id']);

        if ($seUpdate) {
            $seUpdate->update([
                'candidato' => $datos['candidato'],
                'situacion' => $datos['situacion'],
                'email' => $datos['email'],
                'calle' => $datos['calle'],
                'numero_exterior' => $datos['numero_exterior'],
                'colonia' => $datos['colonia'],
                'municipio' => $datos['municipio'],
                'estado' => $datos['estado_columna'],
                'codigo_postal' => $datos['codigo_postal'],
                'pais' => $datos['pais'],
                'latitud' => $datos['latitud'],
                'longitud' => $datos['longitud'],
                'direccion' => $datos['direccion']
            ]);
        }

        //return response()->json($datos);

        if ($padreUpdate) {
            $padre = FamiliasPadres::find($padreUpdate['id']); // Busca el registro por ID
            if ($padre) {
                $padre->update($padreUpdate); // Actualiza los campos permitidos por $fillable
            }
        }
        if ($madreUpdate) {
            $madre = FamiliasPadres::find($madreUpdate['id']); // Busca el registro por ID
            if ($madre) {
                $madre->update($madreUpdate); // Actualiza los campos permitidos por $fillable
            }
        }
        return response()->json(['message' => 'Se hizo update de los datos.']);
    }

    public function descargarFormatoAltaFamiliasMasiva()
    {
        $filePath = 'file_system/formato_test.xlsx'; // Ruta relativa en storage/app/public
        if (Storage::disk('public')->exists($filePath)) {
            return response()->download(storage_path("app/public/{$filePath}"));
        }
        return response()->json(['message' => 'Archivo no encontrado'], 404);
    }

    public function crearDireccion($data)
    { // FUNCION PARA GENERAR DIRECCION PARA BUSCAR EN API
        $numero_exterior = $data['numero_exterior'] ?? null;
        $calle = $data['calle'] ?? null;
        $colonia = $data['colonia'] ?? null;
        $municipio = $data['municipio'] ?? null;
        $estado = $data['estado'] ?? null;
        $codigo_postal = $data['codigo_postal'] ?? null;
        $pais = $data['pais'] ?? null;

        return ($numero_exterior ? $numero_exterior . "," : "") .
            ($calle ? $calle . "," : "") .
            ($colonia ? $colonia . "," : "") .
            ($municipio ? $municipio . "," : "") .
            ($estado ? $estado . "," : "") .
            ($codigo_postal ? $codigo_postal . "," : "") .
            ($pais ? $pais : "");
    }

    public function cargaMasivaFamilias(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx',
        ]);
        $totalInserts = 0;
        $id_cliente = $request->id_cliente;
        $id_proyecto = $request->id_proyecto;
        $id_orden_servicio = $request->id_orden_servicio;
        //$altaFamilia = $request->enableAltaFamilia == 'true' ? true : false;// false;
        $asignarColaboradorReq = $request->enableAsignarColaborador == 'true' ? true : false; //false;
        $file = $request->file('file'); // Obtener el archivo
        $extension = strtolower($file->getClientOriginalExtension());
        $data = [];
        $dataToInsert = [];
        $familiasNoAsignadas = [];
        $usuariosExistentes = [];
        $userReactivados = [];

        if ($extension === 'csv' || $extension === 'txt') {
            if (($handle = fopen($file->getPathname(), 'r')) !== false) {
                // Leer la primera fila como encabezados
                $headers = fgetcsv($handle);
                $headers = array_map(function($header) {
                    $encoding = mb_detect_encoding($header, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
                    return mb_convert_encoding($header, 'UTF-8', $encoding ?: 'UTF-8');
                }, $headers);

                $data = []; // Aquí almacenaremos las filas procesadas

                while (($row = fgetcsv($handle)) !== false) {

                    $row = array_map(function($value) {
                        $encoding = mb_detect_encoding($value, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
                        return mb_convert_encoding($value, 'UTF-8', $encoding ?: 'UTF-8');
                    }, $row);

                    // Asegurar que las filas coincidan en tamaño con los encabezados
                    $row = array_pad($row, count($headers), 'SIN DATO');

                    // Combinar encabezados con valores
                    $data[] = array_combine($headers, $row);
                }

                fclose($handle);
            }
        }

        if ($extension === 'xlsx') {
            try {
                //code...
                $spreadsheet = IOFactory::load($file->getPathname());
            } catch (\Throwable $th) {
                //throw $th;
                return response()->json([
                    'error' => 'Error al procesar el archivo.',
                    'message' => $th->getMessage()
                ], 500);
            }

            $worksheet = $spreadsheet->getActiveSheet();

            $data = [];

            foreach ($worksheet->getRowIterator() as $row) {
                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    $value = $cell->getValue();
                    $rowData[] = mb_convert_encoding($value, 'UTF-8', 'auto');
                }
                $data[] = $rowData;
            }

            // Limpiar encabezados eliminando columnas vacías
            if (!empty($data)) {
                $headers = $data[0];

                // Identificar índices de columnas vacías
                $validColumns = array_keys(array_filter($headers, function($h) {
                    return trim($h) !== "";
                }));

                // Filtrar encabezados
                $headers = array_intersect_key($headers, array_flip($validColumns));
                $data[0] = array_values($headers); // Reindexar

                // Filtrar las filas de datos para que solo mantengan las mismas columnas que el header
                foreach ($data as $index => $row) {
                    $data[$index] = array_values(array_intersect_key($row, array_flip($validColumns)));
                }
            }

        }

        $headers = $data[0]; // Primer array son los headers
        $rows = array_slice($data, 1); // Resto de los datos

        $formattedData = array_map(function ($row) use ($headers) {
            return array_combine($headers, $row);
        }, $rows);

        //return response()->json(['data'=> $formattedData]);

        DB::beginTransaction();
        try {
            foreach ($formattedData as $familiaPorCrear) {
                $existe = User::select('*')->where('email', $familiaPorCrear['Email_cuenta'])->first();
                //return response()->json(['message' => count($existe)]);
                if ($existe !== null) {
                    //array_push($usuariosExistentes, ["error" => "Email previamente registrado", 'tipo' => 'existe', "familia" => $existe]);
                    $existe->activo = true;
                    $existe->active = true;
                    $passReactive = $this->generarContraseñaTemporal();
                    $existe->password = bcrypt($passReactive);
                    $existe->password_temporal = $passReactive;
                    $existe->save();
                    array_push($userReactivados, [ 'email' => $existe->email, 'name' => $existe->name ]);
                    //return response()->json(['message' => 'Existe el suser ' . $familiaPorCrear['Email_cuenta']]);
                } else {
                    // enpieza el insert
                    //1. crear USUARIO

                    $newUser = $familiaPorCrear;
                    $newUser['id_perfil'] = 6;
                    $newUser['id_cliente'] = $id_cliente;
                    $newUser['password_temporal'] = $this->generarContraseñaTemporal();
                    $newUser['externo'] = 1;

                    $pass = $this->generarContraseñaTemporal();
                    $dataDireccion = [
                        'numero_exterior' => $familiaPorCrear['Numero_exterior'],
                        'calle' => $familiaPorCrear['Calle'],
                        'colonia' => $familiaPorCrear['Colonia'],
                        'municipio' => $familiaPorCrear['Municipio'],
                        'estado' => $familiaPorCrear['Estado'],
                        'codigo_postal' => $familiaPorCrear['Codigo_postal'],
                        'pais' => $familiaPorCrear['Pais'],
                    ];


                    $direccion = $this->crearDireccion($dataDireccion);

                    //$url = "https://nominatim.openstreetmap.org/search?q=". str_replace(' ', '%', $direccion) . "&format=json&addressdetails=1";
                    //return response()->json($url);
                    // Realiza la petición GET
                    //$response = Http::get($url);
                    /* $response = Http::get('https://nominatim.openstreetmap.org/search', [
                        'q' => $direccion,
                        'format' => 'json',
                        'addressdetails' => 1,
                    ]); */

                    $response = Http::withHeaders([
                        'User-Agent' => 'SinergiaEstudiosMX/1.0', // Configuración del User-Agent
                    ])->get('https://nominatim.openstreetmap.org/search', [
                        'q' => $direccion,
                        'format' => 'json',
                        'addressdetails' => 1,
                    ]);

                    if ($response->successful()) {
                        $dataResp = json_decode($response->body());
                        if (empty($dataResp)) {
                            //return response()->json(['data' => 'No contiene datos']);
                            $lat = null;
                            $lon = null;
                        } else {
                            //return response()->json(['datos' => $dataResp, 'estatus' => true]);
                            $direccion = $dataResp[0]->display_name; //$display_name;// = $dataResp[0]->display_name;
                            //return response()->json(['direccion' => $display_name, 'estatus' => true]);
                            $lat = $dataResp[0]->lat;
                            $lon = $dataResp[0]->lon;
                        }
                    }


                    $newUser = [
                        'name' => $familiaPorCrear['Nombre'] === '' ? null : $familiaPorCrear['Nombre'],
                        'email' => $familiaPorCrear['Email_cuenta'] === '' ? null : $familiaPorCrear['Email_cuenta'],
                        'id_perfil' => 6,
                        'id_cliente' => intval($id_cliente, 10) ,
                        'password' => bcrypt($pass),
                        'password_temporal' => $pass,
                        'latitud' => $lat ?? null,
                        'longitud' => $lon ?? null,
                        'direccion' => $direccion,
                        'calle' => $familiaPorCrear['Calle'] ?? null,
                        'numero_exterior' => strval($familiaPorCrear['Numero_exterior'] ) ?? '',
                        'colonia' => $familiaPorCrear['Colonia'] ?? null,
                        'municipio' => $familiaPorCrear['Municipio'] ?? null,
                        'estado' => $familiaPorCrear['Estado'] ?? null,
                        'codigo_postal' => strval($familiaPorCrear['Codigo_postal']) ?? null,
                        'pais' => $familiaPorCrear['Pais'] ?? null,
                        'externo' => 1,
                    ];

                    array_push($dataToInsert, $newUser);

                    $validator = Validator::make($newUser, [
                        'name' => 'required|present|string|max:255',
                        'email' => ['required', 'email:rfc,dns','regex:/^[^@]+@[^@]+\.[a-z]{2,}$/i', 'max:100', 'unique:users', 'present'],
                        'id_perfil' => 'required|int',
                        'id_cliente' => 'nullable|int',
                        'latitud' => 'nullable|string',
                        'longitud' => 'nullable|string',
                        'direccion' => 'nullable|string',
                        'calle' => 'nullable|string',
                        'numero_exterior' => 'nullable|string',
                        'colonia' => 'nullable|string',
                        'municipio' => 'nullable|string',
                        'estado' => 'nullable|string',
                        'codigo_postal' => 'nullable|string',
                        'pais' => 'nullable|string',
                        'externo' => 'boolean',
                    ]);

                    if ($validator->fails()) {
                        $errorsRow = $validator->errors();
                        array_push($usuariosExistentes, [
                            "error" => "Formato no válido. Revisar datos ingresados de las familias.",
                            "errorValidate" => $errorsRow->toArray(),
                            'tipo' => 'validador',
                            "familia" => $newUser
                        ]);
                        continue; // Detiene el flujo si hay errores de validación
                    }

                    $user = User::create($newUser);
                    $userID = $user->id;

                    //2. crear Caso Servicio Estudio
                    $directorio = $this->setDirectorioEstudio($userID);

                    $newServicioEconomico = [
                        'id_servicio_estado' => 1,
                        'id_proyecto' => $id_proyecto,
                        'id_cliente' => $id_cliente,
                        'id_familia' => $userID,
                        'id_orden_servicio' => $id_orden_servicio,
                        'es_cliente_comun' => 0,
                        'directorio' => $directorio,
                        'candidato' => $familiaPorCrear['Familia'],
                        'situacion' => 'EN PROCESO',
                        'email' => $familiaPorCrear['Email_cuenta'],
                        'direccion' => 'PENDIENTE',
                        'calle' => $familiaPorCrear['Calle'],
                        'numero_exterior' => $familiaPorCrear['Numero_exterior'],
                        'colonia' => $familiaPorCrear['Colonia'],
                        'municipio' => $familiaPorCrear['Municipio'],
                        'estado' => $familiaPorCrear['Estado'],
                        'codigo_postal' => $familiaPorCrear['Codigo_postal'],
                        'pais' => $familiaPorCrear['Pais'],
                        'clave_familia_colegio'=> $familiaPorCrear['Clave_familia']
                    ];

                    $servNew = ServicioEstudio::create($newServicioEconomico);

                    $newPadre = [
                        'id_familias_padres_tipo' => 1,
                        'nombre' => strtolower($familiaPorCrear['Es_Padre']) == 'x' ? $familiaPorCrear['Nombre'] : '',
                        'vive' => $familiaPorCrear['Padre_vive'] == 'si' ? 1 : 0,
                        'direccion' => $direccion,
                        'email' => strtolower($familiaPorCrear['Es_Padre']) == 'x' ? $familiaPorCrear['Email_cuenta'] : '',
                        'id_servicio_estudio' => $servNew->id,
                        'contecto_principal' => strtolower($familiaPorCrear['Es_Padre']) == 'x' ? 1 : 0,
                        'edad' => 0

                    ];

                    $newMadre = [
                        'id_familias_padres_tipo' => 2,
                        'nombre' => strtolower($familiaPorCrear['Es_Madre']) == 'x' ? $familiaPorCrear['Nombre'] : '',
                        'vive' => $familiaPorCrear['Es_Madre'] == 'si' ? 1 : 0,
                        'direccion' => $direccion,
                        'email' => strtolower($familiaPorCrear['Es_Madre']) == 'x' ? $familiaPorCrear['Email_cuenta'] : '',
                        'id_servicio_estudio' => $servNew->id,
                        'contecto_principal' => strtolower($familiaPorCrear['Es_Madre']) == 'x' ? 1 : 0,
                        'edad' => 0

                    ];

                    FamiliasPadres::create($newPadre);
                    FamiliasPadres::create($newMadre);
                    //$addPadreMadre = FamiliasPadres

                    if ($asignarColaboradorReq) {
                        if ($lat !== null && $lon !== null) { // se asginan colaboradres
                            //DB::rollBack();
                            $colabs = User::where('id_perfil', 4)->where('active', 1)->get();
                            $userFamiliaDistancia = [];
                            foreach ($colabs as $colab) {
                                if ($colab->latitud && $colab->longitud) {
                                    $distancia = $this->calcularDistanciaColabFamilia(floatval($colab->latitud), floatval($colab->longitud), floatval($lat), floatval($lon));
                                    array_push($userFamiliaDistancia, ['distancia' => $distancia, 'calab' => $colab->id, 'se' => $servNew->id]);
                                }
                            }

                            $minDistancia = collect($userFamiliaDistancia)->sortBy('distancia')->first();
                            if ($minDistancia) {
                                // Actualizar el registro en la base de datos
                                $servNew->update([
                                    'id_colaborador' => $minDistancia['calab']
                                ]);
                            }
                            //return response()->json(['colabs' => $colabs, 'servcreado' => $servNew, 'comparacion' => $userFamiliaDistancia]);
                        } else {
                            array_push($familiasNoAsignadas, ['familia' => $servNew]);
                        }
                    }

                    $totalInserts++;
                }
            }
            /* DB::rollBack();
            return response()->json(['dataToInsert' => $dataToInsert]); */
            if (!empty($usuariosExistentes)) {
                DB::rollBack();
                return response()->json([
                    'data' => $data, 'dataToInsert' => [],
                    'total_insert' => $totalInserts,
                    'errors' => $usuariosExistentes,
                    'estatus' => 'fallido',
                    'no_asignadas' => $familiasNoAsignadas,
                    'reactivados' => $userReactivados]);
            }



            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack(); // Deshace todos los cambios si ocurre un error

            // Puedes manejar el error aquí, como registrar un log o devolver un mensaje de error
            return response()->json(['error' => $e->getMessage()], 500);
        }
        // Devolver el array procesado como respuesta JSON (para pruebas)
        return response()->json(['data' => $data, 'dataToInsert' => $dataToInsert, 'total_insert' => $totalInserts,
        'errors' => $usuariosExistentes, 'estatus' => 'completo',
        'no_asignadas' => $familiasNoAsignadas, 'reactivados' => $userReactivados]);
    }

    public function editar(Request $request, $id)
    {

        $servicio = ServicioEstudio::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'id_servicio_estado' => 'required|exists:servicio_estados,id',
            'id_orden_servicio' => 'required|exists:ordenes_servicio,id',
            'id_familia' => 'required|exists:familias,id',
            'id_cliente' => 'required|exists:clientes,id',
            'id_colaborador' => 'null|exists:users,id',
            'es_familia_comun' => 'null|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $editar = $servicio->update($validator->validate());

        return response()->json(['message' => 'Elemento modificado', 'data' => $editar], 201);
    }

    public function enviarCorreo($data)
    {
        // Enviar el correo
        Mail::to(['davidsotord93@gmail.com', 'mrr20012@gmail.com', 'mrr2001@hotmail.com'])->send(new NotificacionCorreo($data));
    }


    public function rejistroSocioeconomico(Request $request)
    {

        $contacto_por_defecto = array();
        $contacto_por_defecto_es = '';
        $id_familia = null;

        $messages = [
            'candidato.required' => 'El nombrede familia es requerido.',
            'padre.nombre.required' => 'El nombre del padre es requerido.',
            'madre.nombre.required' => 'El nombre del madre es requerido.',
            'padre.nombre.regex' => 'El email del padre no puede contiener espacios.',
            'madre.nombre.regex' => 'El email de la madre no puede contiener espacios.',
        ];


        $validator = Validator::make($request->all(), [
            //Validar datos de solicitud
            'id_servicio_estado' => 'required|exists:servicio_estados,id',
            'id_proyecto' => 'required|exists:proyectos,id',
            'id_cliente' => 'required|exists:clientes,id',
            'id_orden_servicio' => 'required|exists:ordenes_servicio,id',
            'id_colaborador' => 'nullable|exists:users,id',
            'es_familia_comun' => 'nullable|boolean',
            'candidato' => 'required|string|max:255',
            'situacion' => 'required|string|max:500',
            'generar_usuario_automaticamente' => 'nullable|boolean',

            'calle' => 'nullable|string|max:120',
            'numero_exterior' => 'nullable|string|max:10',
            'colonia' => 'nullable|string|max:60',
            'municipio' => 'nullable|string|max:60',
            'estado' => 'nullable|string|max:60',
            'codigo_postal' => 'nullable|string|max:5',
            'pais' => 'nullable|string|max:60',

            'direccion' => 'nullable|string|max:255',
            'latutud' => 'nullable|numeric|max:255',
            'longitud' => 'nullable|numeric|max:255',

            //Validar datos de padrre
            'padre' => 'nullable|array', // Permite que el array sea opcional
            'padre.id_familias_padres_tipo' => 'nullable|integer|exists:familias_padres_tipos,id',
            'padre.nombre' => 'nullable|string|max:255',
            'padre.edad' => 'nullable|integer|min:0',
            'padre.vive' => 'nullable|boolean',
            'padre.direccion' => 'nullable|string|max:255',
            'padre.ocupacion_actual' => 'nullable|string|max:255',
            'padre.empresa_trabajo' => 'nullable|string|max:255',
            'padre.email' => ['nullable', 'email:rfc', 'max:255', 'regex:/^\S*$/u'],
            'padre.telefono_casa' => 'nullable|string|max:15',
            'padre.contecto_principal' => 'nullable|boolean',

            //Validar datos de madre
            'madre' => 'nullable|array', // Permite que el array sea opcional
            'madre.id_familias_padres_tipo' => 'nullable|integer|exists:familias_padres_tipos,id',
            'madre.nombre' => 'nullable|string|max:255',
            'madre.edad' => 'nullable|integer|min:0',
            'madre.vive' => 'nullable|boolean',
            'madre.direccion' => 'nullable|string|max:255',
            'madre.ocupacion_actual' => 'nullable|string|max:255',
            'madre.empresa_trabajo' => 'nullable|string|max:255',
            'madre.email' => ['nullable', 'email:rfc', 'max:255', 'regex:/^\S*$/u'],
            'madre.telefono_casa' => 'nullable|string|max:15',
            'madre.contecto_principal' => 'nullable|boolean',

        ], $messages);


        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }

        $padre_request_validar = $request->padre;
        //$padre_request['email'] = $padre_request['email']=== null ? 'SIN DATO' : $padre_request['email'];
        $madre_request_validar = $request->madre;
        $emailMadreExist = FamiliasPadres::where('email', $madre_request_validar['email'])->first();
        $emailPadreExist = FamiliasPadres::where('email', $padre_request_validar['email'])->first();

        if ($emailMadreExist) {
            return response()->json([
                "errors" => [
                    'madre.email' => ['Email de Madre se encuentra registrado.'],
                ]
            ], 400);
        }

        if ($emailPadreExist) {
            return response()->json([
                "errors" => [
                    'padre.email' => ['Email de Padre se encuentra registrado.'],
                ]
            ], 400);
        }

        if ($request->padre["contecto_principal"]) {
            $contacto_por_defecto = $request->padre;
            $contacto_por_defecto['edad'] = $contacto_por_defecto['edad'] === null ? 0 : $contacto_por_defecto['edad'];
            $contacto_por_defecto_es = 'padre';
            if ($contacto_por_defecto['nombre'] === null || $contacto_por_defecto['email'] === null) {
                return response()->json([
                    "errors" => [
                        'padre.nombre' => ['Nombre de Contacto Principal es REQUERIDO'],
                        'padre.email' => ['Email de Contacto Principal es REQUERIDO'],
                    ]
                ], 400);
            }

        } else if ($request->madre["contecto_principal"]) {
            $contacto_por_defecto = $request->madre;
            $contacto_por_defecto['edad'] = $contacto_por_defecto['edad'] === null ? 0 : $contacto_por_defecto['edad'];
            $contacto_por_defecto_es = 'madre';
            if ($contacto_por_defecto['nombre'] === null || $contacto_por_defecto['email'] === null) {
                return response()->json([
                    "errors" => [
                        'madre.nombre' => ['Nombre de Contacto Principal es REQUERIDO'],
                        'madre.email' => ['Email de Contacto Principal es REQUERIDO'],
                    ]
                ], 400);
            }
        } else {
            return response()->json([
                "errors" => [
                    'padre.contecto_principal' => ['Seleccione un contacto principal'],
                    'madre.contecto_principal' => ['Seleccione un contacto principal'],
                ]
            ], 400);
        }
        //validar si ya esisite un contacot con en la orden de servicio con el mismo email
        $estudio_contacto = ServicioEstudio::with(['familiasPadres' => function ($query) use ($contacto_por_defecto) {
            $query
                ->where('email', $contacto_por_defecto["email"]);
            //->where('contecto_principal',true);
        }])->where('id_orden_servicio', $request->id_orden_servicio)->first();

        /* if($estudio_contacto && count($estudio_contacto->familias_padres)){
            return response()->json([
                "errors"=>[
                    $contacto_por_defecto_es.'.contecto_principal' => ['Contacto principal ya registrado en esta orden de servicio'],
                    ]
            ], 400);
        }
        */

        $user = User::where('email', $contacto_por_defecto["email"])->first();

        if ($user) {
            $id_familia = $user->id;
        } else {
            if ($request->generar_usuario_automaticamente == true) {
                $password_temposral =  $this->generarContraseñaTemporal();

                $usuario_familia = User::create([
                    'name' => $contacto_por_defecto["nombre"],
                    'email' => $contacto_por_defecto["email"],
                    'id_cliente' => $request->id_cliente,
                    'id_perfil' => 6,
                    'password' => bcrypt($password_temposral),
                    'password_temporal' => $password_temposral,
                    'externo' => 1
                ]);

                $id_familia =  $usuario_familia->id;

                $this->enviarCorreo($usuario_familia);
            }
        }



        $elemento = ServicioEstudio::create(array_merge(
            $validator->validate(),
            ['id_familia' => $id_familia]
        ));

        $id_servicio_estudio = $elemento->id;
        $colegios_comunes = $request->colegios_comunes;

        $padre = array();
        $madre = array();


        if (isset($colegios_comunes) and isset($id_servicio_estudio)) {
            foreach ($colegios_comunes as $id_colegio_comun) {
                ServiciosEstudiosClientesComunes::create(['id_servicio_estudio' => $id_servicio_estudio, 'id_cliente' => $id_colegio_comun]);
            }

            $padre_request = $request->padre;
            $padre_request['edad'] = $padre_request['edad']=== null ? 0 : $padre_request['edad'];
            $padre_request['direccion'] = $padre_request['direccion']=== null ? 'SIN DATO' : $padre_request['direccion'];
            $padre_request['nombre'] = $padre_request['nombre']=== null ? 'SIN DATO' : $padre_request['nombre'];
            $padre_request['email'] = $padre_request['email']=== null ? 'SIN DATO' : $padre_request['email'];

            $madre_request = $request->madre;
            $madre_request['edad'] = $madre_request['edad']=== null ? 0 : $madre_request['edad'];
            $madre_request['direccion'] = $madre_request['direccion']=== null ? 'SIN DATO' : $madre_request['direccion'];
            $madre_request['nombre'] = $madre_request['nombre']=== null ? 'SIN DATO' : $madre_request['nombre'];
            $madre_request['email'] = $madre_request['email']=== null ? 'SIN DATO' : $madre_request['email'];


            $padre = FamiliasPadres::create(array_merge(
                $padre_request,
                [
                    'id_servicio_estudio' => $id_servicio_estudio
                ]
            ));
            $madre = FamiliasPadres::create(array_merge(
                $madre_request,
                [
                    'id_servicio_estudio' => $id_servicio_estudio
                ]
            ));

            $elemento['padre'] = $padre;
            $elemento['madre'] = $madre;
        }


        $directorio = $this->setDirectorioEstudio($elemento->id);
        if ($directorio != '') {
            $elemento['directorio'] = $directorio;
        }

        return response()->json(['message' => 'Nuevo elemento creado', 'data' => $elemento], 201);
    }


    private function generarContraseñaTemporal()
    {
        $dataSetCaracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $mesclar = str_shuffle($dataSetCaracteres);
        $nuevaContraseña = substr($mesclar, 0, 8);
        return $nuevaContraseña;
    }

    private function setDirectorioEstudio($id)
    {
        $directorio = '';

        $editar = ServicioEstudio::where('id', $id)->first();
        if (!$editar) {
            return '';
        }

        $proyecto = Proyectos::where('id', $editar->id_proyecto)->first();
        $directorio .= $this->limpiarCadena($proyecto->nombre);
        $ordenServicio = OrdenesServicio::where('id', $editar->id_orden_servicio)->first();
        $directorio .= "/" . $ordenServicio->id . '_' . $this->limpiarCadena($ordenServicio->descripcion);
        $directorio .= "/" . $id . '_' . $this->limpiarCadena($editar->candidato);


        $editar->directorio = $directorio . "/";
        $editar->save();

        return $directorio;
    }

    function limpiarCadena($cadena)
    {
        // Convertir los espacios en guiones bajos
        $cadena = str_replace(' ', '_', $cadena);

        // Eliminar todos los caracteres que no sean letras o números (quitar caracteres especiales)
        $cadena = preg_replace('/[^a-zA-Z0-9_]/', '', $cadena);

        return $cadena;
    }

    public function calcularDistanciaColabFamilia($lat1, $lon1, $lat2, $lon2)
    {
        //$lat_familia, $let_familia
        $radioTierra = 6371; // Radio de la Tierra en kilómetros o millas

        // Convertir grados a radianes
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Diferencias de latitud y longitud
        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        // Fórmula del haversine
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Distancia
        return $radioTierra * $c;
    }

    public function asignarColaborador($id_estudio = 0, Request $request)
    {
        if (!$id_estudio) {
            return response()->json([
                "errors" => [
                    'estudio' => ['No se recibió estudio'],
                ]
            ], 400);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'id_colaborador' => 'required|exists:user,id',
            ]
        );

        $elemento = ServicioEstudio::where('id', $id_estudio)->first();
        $elemento->id_colaborador = $request->id_colaborador;
        $elemento->save();

        return response()->json(['message' => 'Elemento guardado', 'data' => $elemento], 201);
    }

    public function preasignarEstudios(Request $request)
    {

        $messages = [
            'id_servicios_estudio.array' => 'Seleccione un estudio.',
            'id_colaborador.required' => 'Seleccione un colaborador.',
            'id_colaborador.exists' => 'El colaborador no exsiste.',
        ];

        $validator = Validator::make(
            $request->all(),
            [
                'id_colaborador' => 'required|exists:user,id',
                'id_servicios_estudio' => 'required|array',
            ],
            $messages
        );

        $elemento = ServicioEstudio::whereIn('id', $request->id_servicios_estudio)
            //->whereNotNull('id_servicio_estado', 2)
            ->update(['id_servicio_estado' => 2, 'id_colaborador' => $request->id_colaborador]);


        return response()->json(['message' => 'Elemento guardado', 'data' => $elemento], 201);
    }
    public function asignarEstudios(Request $request)
    {

        $messages = [
            'id_servicios_estudio.array' => 'Seleccione un estudio.',
        ];

        $validator = Validator::make(
            $request->all(),
            [
                'id_servicios_estudio' => 'required|array',
            ],
            $messages
        );

        $total = 0;
        $total = count($request->id_servicios_estudio);

        $elementos = ServicioEstudio::whereIn('id', $request->id_servicios_estudio)
            ->whereNotNull('id_colaborador')
            ->update(['id_servicio_estado' => 2]);

        $message = 'Elemenost asignados';
        if ($total != $elementos) {
            $message = 'Elemenost asignados ' . $elementos . ' de ' . $total . ', ' . ($total - $elementos) . ' sin colaborador asignado';
        }

        return response()->json(['message' => $message, 'data' => $elementos], 201);
    }
    public function asignarCalidad(Request $request)
    {

        $messages = [
            'id_servicios_estudio.array' => 'Seleccione un estudio.',
            'id_calidad.required' => 'Seleccione un colaborador.',
            'id_calidad.exists' => 'El colaborador no exsiste.',
        ];

        $validator = Validator::make(
            $request->all(),
            [
                'id_calidad' => 'required|exists:user,id',
                'id_servicios_estudio' => 'required|array',
            ],
            $messages
        );

        $total = 0;
        $total = count($request->id_servicios_estudio);

        $elementos = ServicioEstudio::whereIn('id', $request->id_servicios_estudio)
            ->whereNotNull('id_servicio_estado', 2)
            ->update(['id_servicio_estado' => 3, 'id_calidad' => $request->id_calidad]);

        $message = 'Elementos enviados a calidad';
        if ($total != $elementos) {
            $message = 'Elementos enviados a calidad ' . $elementos . ' de ' . $total . ', ' . ($total - $elementos) . ' aun requieren infomracion';
        }

        return response()->json(['message' => $message, 'data' => $elementos], 201);
    }
    public function encuesta($id_estudio = 0)
    {
        if (!$id_estudio) {
            return response()->json([], 201);
        }

        $estudio = ServicioEstudio::where('id', $id_estudio)->first();


        $id_proyecto = $estudio->id_proyecto;
        $id_cliente = $estudio->id_cliente;

        $preoyecto_cliente = ProyectosClientes::where('id_proyecto', $id_proyecto)->where('id_cliente', $id_cliente)->first();
        $id_encuesta = $preoyecto_cliente->id_encuesta;

        $encuesta = CatalogoEncuestas::where('id', $id_encuesta)->first();

        return response()->json($encuesta);
    }

    public function addFechaVisita(Request $request, $id = 0)
    {

        // Validar los datos entrantes
        $validator = Validator::make($request->all(), [
            'visita_fecha' => 'nullable|date',
            'visita_hora' => 'nullable|date_format:H:i',
            'visita_recordatorio' => 'nullable|string|max:1200',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $solicitud = ServicioEstudio::find($id);

        if (!$solicitud) {
            return response()->json([
                "errors" => [
                    'estudio' => ['Solicitud de estudio no encontrada.'],
                ]
            ], 400);
        }

        $solicitud->visita_fecha = $request->visita_fecha;
        $solicitud->visita_hora = $request->visita_hora;
        $solicitud->visita_recordatorio = $request->visita_recordatorio;

        $solicitud->save();

        return response()->json([
            'message' => 'Solicitud de estudio actualizada exitosamente.',
            'data' => $solicitud
        ], 200);
    }

    public function estudioSocioeconomico(Request $request,$id)
    {

        /*with(['estado','cliente','proyecto','ordenServicio','colaborador','colegiosComunes',])->*/
        //$elemento['encuesta'] = $encuesta;
        $id_hijo = 0;
        if(isset($request->id_hijo)){
            $id_hijo = $request->id_hijo;
        }

        $elemento = ServicioEstudio::with(['cliente'])->where('id', $id)->first();

        $proyectoCliente = ProyectosClientes::with(['encuesta', 'encuesta.preguntas', 'proyecto'])->where('id_proyecto', $elemento->id_proyecto)->where('id_cliente', $elemento->id_cliente)->first();

        foreach ($proyectoCliente->encuesta->preguntas as &$pregunta) {
            $pregunta['respuestas'] = ServiciosEstudiosRespuestas::where('id_servicio_estudio', $elemento->id)->where('id_catalogo_encuestas_pregunta', $pregunta->id)->get();
        }

        $encuesta = $proyectoCliente->encuesta;
        $encuesta['estudio'] = $elemento;
        $encuesta['proyecto'] = $proyectoCliente->proyecto;
        //$encuesta['cliente'] = $elemento->cliente;
        if($id_hijo){
            $encuesta['hijo'] = ServiciosEstudiosRespuestas::where('id', $id_hijo)->first();
        }

        $parametros = CatalogoEncuestasPreguntasParametrosClasificacions::where('id_catalogo_encuesta', $encuesta->id)->get();

        foreach ($parametros as &$parametro) {
            $parametro['puntos'] = $this->puntosPrecuntaSeccion($parametro, $id, $encuesta->preguntas,$id_hijo);
        }
        $encuesta['parametros'] = $parametros;



        return response()->json($encuesta);
    }

    public function estudioSocioeconomicoParametrosPuntos($id)
    {

        $elemento = ServicioEstudio::where('id', $id)->first();

        $proyectoCliente = ProyectosClientes::with(['encuesta.preguntas'])->where('id_proyecto', $elemento->id_proyecto)->where('id_cliente', $elemento->id_cliente)->first();

        $encuesta = $proyectoCliente->encuesta;

        $parametros = CatalogoEncuestasPreguntasParametrosClasificacions::where('id_catalogo_encuesta', $encuesta->id)->get();

        foreach ($parametros as &$parametro) {
            $parametro['puntos'] = $this->puntosPrecuntaSeccion($parametro, $id, $encuesta->preguntas,0);
        }

        //$parametros;

        return response()->json($parametros);
    }

    public function estudioParametrosPuntos($id,$hijo)
    {

        $elemento = ServicioEstudio::where('id', $id)->first();

        $proyectoCliente = ProyectosClientes::with(['encuesta.preguntas'])->where('id_proyecto', $elemento->id_proyecto)->where('id_cliente', $elemento->id_cliente)->first();

        $encuesta = $proyectoCliente->encuesta;

        $parametros = CatalogoEncuestasPreguntasParametrosClasificacions::where('id_catalogo_encuesta', $encuesta->id)->get();

        foreach ($parametros as &$parametro) {
            $parametro['puntos'] = $this->puntosPrecuntaSeccion($parametro, $id, $encuesta->preguntas,$hijo);
        }

        //$parametros;

        return $parametros;
    }

    private function puntosPrecuntaSeccion($parametro, $id_estudio, $preguntas,$hijo = 0)
    {

        $puntos = [];

        $lista_respuestas = array();
        $lista_respuestas_adicinales_uno = array();
        $lista_respuestas_adicinales_dos = array();

        $preguntas_tipo = [];
        $preguntas_lista = [];

        foreach ($preguntas as $pregunta) {

            if ( $parametro->id == $pregunta->id_catalogo_encuestas_preguntas_parametro_clasificacion ) {
                $preguntas_tipo[] = $pregunta->id_catalogo_encuestas_preguntas_tipo;
                $respuestas = [];
                $respuestas_array = [];

                $respuestas = ServiciosEstudiosRespuestas::where('id_servicio_estudio', $id_estudio)
                    ->where('id_catalogo_encuestas_pregunta', $pregunta->id)
                    ->get();

                $respuestas_array = $respuestas->toArray();

                if (!count($lista_respuestas)) {
                    $lista_respuestas = $respuestas_array;
                } else {
                    if (count($respuestas_array)) {
                        $lista_respuestas = array_merge($lista_respuestas, $respuestas_array);
                    }
                }
                //$respuestas;//$this -> calculoDePuntosPorTipo($pregunta,$respuestas);
                //$lista_respuestas[] = ["id_servicio_estudio"=>$id_estudio,"id_catalogo_encuestas_pregunta"=>$pregunta->id,'respuestas'=>$respuestas_array]; // $this -> sumatoriaRespuesta($respuestas);

                if ( $pregunta->id_parametro_clasificacion_tipo){

                    $pregunta_temp = array();
                    $pregunta_temp = clone $pregunta;
                    $pregunta_temp["respuestas"] = $respuestas_array;
                    $preguntas_lista[] = $pregunta_temp;
                }
            }

            if ( $parametro->id == $pregunta->id_parametro_clasificacion_parametro_adicional_uno ) {
                $preguntas_tipo[] = $pregunta->id_catalogo_encuestas_preguntas_tipo;
                $respuestas = [];
                $respuestas_array = [];
                $respuestas = ServiciosEstudiosRespuestas::where('id_servicio_estudio', $id_estudio)
                    ->where('id_catalogo_encuestas_pregunta', $pregunta->id)
                    ->get();

                $respuestas_array = $respuestas->toArray();

                if (!count($lista_respuestas_adicinales_uno)) {
                    $lista_respuestas_adicinales_uno = $respuestas_array;
                } else {
                    if (count($respuestas_array)) {
                        $lista_respuestas_adicinales_uno = array_merge($lista_respuestas_adicinales_uno, $respuestas_array);
                    }
                }

            }

            if ( $parametro->id == $pregunta->id_parametro_clasificacion_parametro_adicional_dos ) {
                $preguntas_tipo[] = $pregunta->id_catalogo_encuestas_preguntas_tipo;
                $respuestas = [];
                $respuestas_array = [];
                $respuestas = ServiciosEstudiosRespuestas::where('id_servicio_estudio', $id_estudio)
                    ->where('id_catalogo_encuestas_pregunta', $pregunta->id)
                    ->get();

                $respuestas_array = $respuestas->toArray();

                if (!count($lista_respuestas_adicinales_dos)) {
                    $lista_respuestas_adicinales_dos = $respuestas_array;
                } else {
                    if (count($respuestas_array)) {
                        $lista_respuestas_adicinales_dos = array_merge($lista_respuestas_adicinales_dos, $respuestas_array);
                    }
                }

            }
        }

        //return $lista_respuestas;

        $total = 0;

        //$pregunta = $this->preguntaEsporHijo($elemento->id_proyecto,$elemento->id_cliente);
        $datos_hijo = [];
        if($hijo != 0){
            $datos_hijo = ServiciosEstudiosRespuestas::
                where('id',$hijo)
                //where('id_servicio_estudio',$estudio->id)
                //->where('id_catalogo_encuestas_pregunta',$pregunta->id)
                //->where('nombre', '!=', '')
                ->first();
        }



        switch ($parametro->id_catalogo_encuestas_preguntas_parametros_clasificaciones_tipos) {
            case 1:
                if(in_array(3,$preguntas_tipo)){
                    $total = (int) $this->opcionSeleccionada("valor",$lista_respuestas);
                    $puntos = $this->obtenerOpcionSeleccionada($parametro->id, $total);
                }else{
                    $sumatorias_por_seccion = $this->sumatoriaRespuesta($lista_respuestas);
                    foreach ($sumatorias_por_seccion as $seccion) {
                        $total += $seccion['padre_monto'];
                        $total += $seccion['madre_monto'];
                        $total += $seccion['monto'];
                    }
                    $puntos = $this->obtenerRango($parametro->id, $total);
                }
                break;
            case 2:
                    /*if(in_array(3,$preguntas_tipo)){
                        $total = (int) $this->opcionSeleccionada("valor",$lista_respuestas);
                        $puntos = $this->obtenerOpcionSeleccionada($parametro->id, $total);
                    }else{
                        $sumatorias_por_seccion = $this->sumatoriaRespuesta($lista_respuestas);
                        foreach ($sumatorias_por_seccion as $seccion) {
                            $total += $seccion['padre_monto'];
                            $total += $seccion['madre_monto'];
                            $total += $seccion['monto'];
                        }
                        $puntos = $this->obtenerRango($parametro->id, $total);
                    }*/
                    $total_puntos = $this->parametroPreguntaPuntos($preguntas_lista);
                    $puntos["secciones"] = $total_puntos;
                    $puntos["respuestas"] = $preguntas_lista;
                    $total = array_sum($total_puntos);
                    $puntos["valor"] = $total;
                    break;
            case 3:
                if(count($lista_respuestas_adicinales_uno)){
                    //calificacionPorCoincidenciaHijo($columna,$hijo,$respuestas)
                    $total =  (string) $this->calificacionPorCoincidenciaHijo("padre_monto",$hijo,$lista_respuestas_adicinales_uno);
                    $puntos = $this->obtenerOpcionSeleccionada($parametro->id, $total);
                    //$puntos["respuestas"] = [$hijo,$lista_respuestas_adicinales_uno];

                }else if(count($lista_respuestas_adicinales_dos)){
                    $total = (string) $this->calificacionPorCoincidenciaHijo("madre_monto",$hijo,$lista_respuestas_adicinales_dos);
                    $puntos = $this->obtenerOpcionSeleccionada($parametro->id, $total);
                    //$puntos["respuestas"] = [$lista_respuestas_adicinales_dos];
                }else{
                    $total =  $this->caluloDeCoincidencia("nombre",$lista_respuestas);
                    $puntos = $this->obtenerCoincidencia($parametro->id, $total);
                    //$puntos["respuestas"] = $lista_respuestas;
                }
                break;
            default:
                $puntos = null;
                break;
        }
        $puntos["sumatoria"] = $total;
        //$items = CatalogoEncuestasPreguntasParametrosClasificacionItems::where('id_catalogo_encuestas_preguntas_parametro_clasificacion',$parametro->id)->get();

        return $puntos;
    }

    private function sumatoriaRespuesta($respuestas)
    {
        $resultado = [];

        foreach ($respuestas as $item) {
            // Si la sección es null, lo ignoramos o puedes manejarlo de otra forma
            if ($item['seccion'] === null) {
                $item['seccion'] = "";
            }

            // Si la sección no está inicializada en el resultado, la creamos
            if (!isset($resultado[$item['seccion']])) {
                $resultado[$item['seccion']] = [
                    'padre_monto' => 0,
                    'madre_monto' => 0,
                    'monto' => 0,
                    'vive' => 0,
                    'activo' => 0,
                    'valor' => 0
                ];
            }

            // Sumar los valores correspondientes a la sección
            $resultado[$item['seccion']]['padre_monto'] += $item['padre_monto'];
            $resultado[$item['seccion']]['madre_monto'] += $item['madre_monto'];
            $resultado[$item['seccion']]['monto'] += $item['monto'];

            // Contar los true en 'vive' y 'activo'
            if ($item['vive']) {
                $resultado[$item['seccion']]['vive'] += 1;
            }

            if ($item['activo']) {
                $resultado[$item['seccion']]['activo'] += 1;
            }

            // Convertir el valor a entero y sumarlo
            $resultado[$item['seccion']]['valor'] += (int)$item['valor'];
        }

        return $resultado;
    }
    private function caluloDeCoincidencia($columna,$respuestas){

        $resultado = 0;

        foreach ($respuestas as $item) {

            // validamos que exsista la columna en la lista de respuestas
            if (isset($item[$columna])) {
                if(strlen($item[$columna])){
                    $resultado++;
                }
            }
        }

        return $resultado;
    }
    function obtenerRango($idParametros, $puntos)
    {
        return CatalogoEncuestasPreguntasParametrosClasificacionItems::where(function ($query) use ($puntos) {
            $query->where(function ($q) use ($puntos) {
                // Caso 1: Limite inferior es 0 (todos los menores a limite superior)
                $q->where('limiten_inferior', 0)
                    ->where('limite_superior', '>', $puntos);
            })
                ->orWhere(function ($q) use ($puntos) {
                    // Caso 2: Limite superior es 0 (todos los mayores a limite inferior)
                    $q->where('limite_superior', 0)
                        ->where('limiten_inferior', '<', $puntos);
                })
                ->orWhere(function ($q) use ($puntos) {
                    // Caso 3: Rango entre limite inferior y limite superior
                    $q->where('limiten_inferior', '<=', $puntos)
                        ->where('limite_superior', '>=', $puntos);
                });
        })
            ->where('id_catalogo_encuestas_preguntas_parametro_clasificacion', $idParametros)->first(); // Devolvemos el primer resultado que coincida
    }
    function opcionSeleccionada($columna,$respuestas){

        $resultado = 0;

        foreach ($respuestas as $item) {

            // validamos que exsista la columna en la lista de respuestas
            if (isset($item[$columna])) {
                $resultado = $item[$columna];
            }
        }

        return $resultado;
    }
    function obtenerCoincidencia($idParametros, $puntos)
    {
        return CatalogoEncuestasPreguntasParametrosClasificacionItems::where(function ($query) use ($puntos) {
            $query->where('limiten_inferior', '=', $puntos);
        })
            ->where('id_catalogo_encuestas_preguntas_parametro_clasificacion', $idParametros)->first(); // Devolvemos el primer resultado que coincida
    }

    function obtenerOpcionSeleccionada($idParametros, $puntos)
    {
        return CatalogoEncuestasPreguntasParametrosClasificacionItems::where(function ($query) use ($puntos) {
            $query->where('valor', '=', $puntos);
        })
            ->where('id_catalogo_encuestas_preguntas_parametro_clasificacion', $idParametros)->first(); // Devolvemos el primer resultado que coincida
    }


    function parametroPreguntaPuntos($listaPreguntas){
        $lista_puntos = [];
        foreach($listaPreguntas AS $pregunta){
            switch($pregunta->id_parametro_clasificacion_tipo){
                case 4:
                    $total = 0;
                    switch($pregunta->id_catalogo_encuestas_preguntas_tipo){
                        case 4:
                            $total =  $this->caluloDeCoincidencia("nombre",$pregunta->respuestas);
                            $puntos = $this->obtenerCoincidenciaPregunta($pregunta->id_parametro_clasificacion_tipo,$pregunta->id_catalogo_encuestas_preguntas, $total);
                            $lista_puntos[] = $total;
                        break;
                        case 5:
                            $total = $this->seleccionUnicaLista("valor",$pregunta->respuestas);
                            $lista_puntos[] = $total;
                        break;
                        case 6:
                            $total =  $this->caluloDeCoincidencia("nombre",$pregunta->respuestas);
                            $puntos = $this->obtenerCoincidenciaPregunta($pregunta->id_parametro_clasificacion_tipo,$pregunta->id_catalogo_encuestas_preguntas, $total);
                            $lista_puntos[] = $total;
                        break;
                        case 7:
                            $sumatorias_por_seccion =  $this->sumatoriaRespuesta($pregunta->respuestas);
                            foreach ($sumatorias_por_seccion as $seccion) {
                                $total += $seccion["activo"];
                            }
                            //$puntos = $this->obtenerCoincidenciaPregunta($pregunta->id_parametro_clasificacion_tipo,$pregunta->id_catalogo_encuestas_preguntas, $total);
                            $rango = $this->obtenerCoincidenciaColumna('limite_superior',$pregunta->id, $pregunta->id_catalogo_encuestas_preguntas_parametro_clasificacion,$total);
                            $puntos = ($rango !== null) ? $rango->valor : null ;
                            $lista_puntos[] = (int) $puntos; // (int) $puntos->valor;
                        break;
                        case 20:
                            $sumatorias_por_seccion =  $this->sumatoriaRespuesta($pregunta->respuestas);
                            foreach ($sumatorias_por_seccion as $seccion) {
                                $total += $seccion["activo"];
                            }
                            //$puntos = $this->obtenerCoincidenciaPregunta($pregunta->id_parametro_clasificacion_tipo,$pregunta->id_catalogo_encuestas_preguntas, $total);
                            $rango = $this->obtenerCoincidenciaColumna('limite_superior',$pregunta->id, $pregunta->id_catalogo_encuestas_preguntas_parametro_clasificacion,$total);
                            $puntos = ($rango !== null) ? $rango->valor : null ;
                            $lista_puntos[] = (int) $puntos; // (int) $puntos->valor;
                        break;
                        default:
                            $total = $this->caluloDeCoincidencia("respuesta",$pregunta->respuestas);
                            $puntos = $this->obtenerCoincidenciaPregunta($pregunta->id_parametro_clasificacion_tipo,$pregunta->id_catalogo_encuestas_preguntas, $total);
                            $lista_puntos[] = $total;
                        break;
                    }
                    //$lista_puntos[$pregunta->id] = $puntos;//["puntos" => $puntos , "total" => $total];
                    //["puntos" => $puntos , "total" => $total];
                break;
                case 5:
                    switch($pregunta->id_catalogo_encuestas_preguntas_tipo){
                        case 13:
                            $total = 0;

                            $respuesta = array_values(array_filter($pregunta->respuestas, function ($p) {
                                return isset($p['seccion']) && $p['seccion'] == 'clasificacion';
                            }));
                            $total = !empty($respuesta) ? (int) $respuesta[0]["respuesta"] : 0;
                            //$total = $this->seleccionUnicaLista("respuesta",$pregunta->respuestas);
                            $lista_puntos[] = $total;
                        break;
                        default:
                            $total = 0;
                            $total = $this->seleccionUnicaLista("valor",$pregunta->respuestas);
                            $lista_puntos[] = $total;
                        break;
                    }
                break;
                /*case 5:
                    $lista_puntos[$pregunta->id]
                break;*/
            }
        }
        return $lista_puntos;
    }

    function obtenerCoincidenciaPregunta($idPregunta,$idParametros, $puntos)
    {
        return CatalogoEncuestasPreguntasParametrosClasificacionItems::where(function ($query) use ($puntos) {
            $query->where('limiten_inferior', '=', $puntos);
        })
            ->where('id_catalogo_encuestas_preguntas', $idPregunta)
            ->where('id_catalogo_encuestas_preguntas_parametro_clasificacion', $idParametros)
            ->first(); // Devolvemos el primer resultado que coincida
    }

    function obtenerCoincidenciaColumna($columna, $idPregunta,$idParametros, $puntos)
    {
        return CatalogoEncuestasPreguntasParametrosClasificacionItems::
              where($columna, '=', $puntos)
            ->where('id_catalogo_encuestas_preguntas', $idPregunta)
            ->where('id_catalogo_encuestas_preguntas_parametro_clasificacion', $idParametros)
            ->first(); // Devolvemos el primer resultado que coincida
    }

    private function calificacionPorCoincidenciaHijo($columna,$hijo,$respuestas){

        $resultado = 0;

        foreach ($respuestas as $item) {

            // validamos que exsista la columna en la lista de respuestas
            if (isset($item[$columna])) {
                if( $item["id"] == $hijo){
                    $resultado = $item[$columna];
                }
            }
        }

        return $resultado;
    }

    private function seleccionUnicaLista($columna,$respuestas){

        $resultado = 0;

        foreach ($respuestas as $index => $item) {

            // validamos que exsista la columna en la lista de respuestas
            if($index == 0){
                if (isset($item[$columna])) {
                    if(strlen($item[$columna])){
                        $resultado = (int) $item[$columna];
                    }
                }
            }
        }

        return $resultado;
    }


    /*private function calculoDePuntosPorTipo($idPreguntaTipo,$respuestas){

    }*/

    private function condicionesEspecialesTotalParametros($pregunta_tipo,$seccion){
        $respuesta_valida =  false;

        switch($pregunta_tipo){
            case 12:
                if($seccion == 'valor' || $seccion == 'body_otros') {
                    $respuesta_valida =  true;
                }
            break;
            default:
                $respuesta_valida =  true;
            break;
        }

        return  $respuesta_valida;
    }

    private function totalPorParametro($lista_preguntas){
        $totalPorParametros = [];

        foreach ($lista_preguntas as $item) {

            $total = 0;

                foreach($item["respuestas"] AS $respuesta){
                    if($this->condicionesEspecialesTotalParametros($item->id_catalogo_encuestas_preguntas_tipo,$respuesta->seccion)){
                        $total += $respuesta["monto"];
                        $total += $respuesta["madre_monto"];
                        $total += $respuesta["padre_monto"];
                    }
                }

                if(array_key_exists($item['id_catalogo_encuestas_preguntas_parametro_clasificacion'],$totalPorParametros)){
                    $totalPorParametros[$item['id_catalogo_encuestas_preguntas_parametro_clasificacion']] += $total;
                }else{
                    $totalPorParametros[$item['id_catalogo_encuestas_preguntas_parametro_clasificacion']] = $total;
                }

                if($item["id_catalogo_encuestas_preguntas_tipo"] == 9 || $item["id_catalogo_encuestas_preguntas_tipo"] == 10 ){

                    if(array_key_exists(-1,$totalPorParametros)){
                        $totalPorParametros[-1] += $total;
                    }else{
                        $totalPorParametros[-1] = $total;
                    }
                }

        }
        return $totalPorParametros;
    }

    private function listaDeDocumentosEstudio($id){

        $secciones = FamiliasDocumentosTipos::get();

        foreach ($secciones as &$seccion) {

            $seccion['documentos'] = [];

            $listaImagenes = FamiliasDocumentos::where('id_servicio_estudio', $id)
                ->where('id_familias_documentos_tipo', $seccion->id)
                ->get();

            if ($listaImagenes->isNotEmpty()) {
                $seccion['documentos'] = $listaImagenes;
            }
        }

        return $secciones;
    }

    private function listaItemParametros($id_pregunta,$id_catalogo_pregunta){

        $id_parametro = CatalogoEncuestasPreguntas::where('id',$id_pregunta)
            ->first()->id_catalogo_encuestas_preguntas_parametro_clasificacion;
        if(!$id_parametro){
            return response()->json([]);
        }
        $query = CatalogoEncuestasPreguntasParametrosClasificacionItems::query();
        $query->where('id_catalogo_encuestas_preguntas_parametro_clasificacion',$id_parametro);

        if($id_catalogo_pregunta){
            $query->where('id_catalogo_encuestas_preguntas', $id_catalogo_pregunta);
        }

        $elementos = $query->get();

        return $elementos;
    }

    public function listaItemParametrosAdicionalUnoItems($id_pregunta){
        $id_parametro = CatalogoEncuestasPreguntas::where('id', $id_pregunta)->first()->id_parametro_clasificacion_parametro_adicional_uno;

        if(!$id_parametro){
            return response()->json([], 200);
        }
        $elementos = CatalogoEncuestasPreguntasParametrosClasificacionItems::where('id_catalogo_encuestas_preguntas_parametro_clasificacion',$id_parametro)->get();

        return $elementos;
    }
    public function listaItemParametrosAdicionalDosItems($id_pregunta){
        $id_parametro = CatalogoEncuestasPreguntas::where('id', $id_pregunta)->first()->id_parametro_clasificacion_parametro_adicional_dos;

        if(!$id_parametro){
            return response()->json([], 200);
        }
        $elementos = CatalogoEncuestasPreguntasParametrosClasificacionItems::where('id_catalogo_encuestas_preguntas_parametro_clasificacion',$id_parametro)->get();

        return $elementos;
    }

    private function generateEstudioSocioeconomicoPDF($id,$id_hijo)
    {

        // Obtener los datos
        $elemento = ServicioEstudio::with(['cliente'])->where('id', $id)->first();

        $proyectoCliente = ProyectosClientes::with(['encuesta', 'encuesta.preguntas', 'proyecto'])->where('id_proyecto', $elemento->id_proyecto)->where('id_cliente', $elemento->id_cliente)->first();

        foreach ($proyectoCliente->encuesta->preguntas as &$pregunta) {
            $pregunta['respuestas'] = ServiciosEstudiosRespuestas::where('id_servicio_estudio', $elemento->id)->where('id_catalogo_encuestas_pregunta', $pregunta->id)->get();

            if( $pregunta->id_catalogo_encuestas_preguntas_tipo == 3){
                    $pregunta['parametros'] = $this->listaItemParametros($pregunta->id,null);

            }else if($pregunta->id_catalogo_encuestas_preguntas_tipo == 13 || $pregunta->id_catalogo_encuestas_preguntas_tipo == 5){
                $pregunta['parametros'] = $this->listaItemParametros($pregunta->id,$pregunta->id);
            }

            if($pregunta->id_catalogo_encuestas_preguntas_tipo == 4){
                $pregunta['parametros_promedio_academico'] = $this->listaItemParametrosAdicionalUnoItems($pregunta->id);
                //getParametrosPromedioAcademico();
                $pregunta['parametros_promedio_conducta']  = $this->listaItemParametrosAdicionalDosItems($pregunta->id);
                //getParametrosPromedioConducta();
            }

        }

        $encuesta = $proyectoCliente->encuesta;
        $encuesta['estudio'] = $elemento;
        $encuesta['proyecto'] = $proyectoCliente->proyecto;
        $encuesta['imagenes'] = $this->listaDeDocumentosEstudio($id);
        //$encuesta['cliente'] = $elemento->cliente;
        if($id_hijo){
            $encuesta['hijo'] = ServiciosEstudiosRespuestas::where('id', $id_hijo)->first();
        }

        $parametros = CatalogoEncuestasPreguntasParametrosClasificacions::where('id_catalogo_encuesta', $encuesta->id)->get();

        foreach ($parametros as &$parametro) {
            $parametro['puntos'] = $this->puntosPrecuntaSeccion($parametro, $id, $encuesta->preguntas,$id_hijo);
        }
        $encuesta['parametros'] = $parametros;
        $encuesta['total_parametros'] = $this->totalPorParametro($encuesta->preguntas);

        //return $encuesta;

        // Cargar la vista y pasar los datos
        $pdf = PDF::loadView('pdf.estudio_socioeconomico', compact('encuesta'))->setPaper('A4', 'portrait');
        return $pdf;
    }

    private function generateDatosEncuestasEstudioSocioeconomicoPDF($id,$id_hijo = 0)
    {

        // Obtener los datos
        $elemento = ServicioEstudio::with(['cliente'])->where('id', $id)->first();

        $proyectoCliente = ProyectosClientes::with(['encuesta', 'encuesta.preguntas', 'proyecto'])->where('id_proyecto', $elemento->id_proyecto)->where('id_cliente', $elemento->id_cliente)->first();

        foreach ($proyectoCliente->encuesta->preguntas as &$pregunta) {
            $pregunta['respuestas'] = ServiciosEstudiosRespuestas::where('id_servicio_estudio', $elemento->id)->where('id_catalogo_encuestas_pregunta', $pregunta->id)->get();

            if( $pregunta->id_catalogo_encuestas_preguntas_tipo == 3){
                    $pregunta['parametros'] = $this->listaItemParametros($pregunta->id,null);

            }else if($pregunta->id_catalogo_encuestas_preguntas_tipo == 13 || $pregunta->id_catalogo_encuestas_preguntas_tipo == 5){
                $pregunta['parametros'] = $this->listaItemParametros($pregunta->id,$pregunta->id);
            }

            if($pregunta->id_catalogo_encuestas_preguntas_tipo == 4){
                $pregunta['parametros_promedio_academico'] = $this->listaItemParametrosAdicionalUnoItems($pregunta->id);
                //getParametrosPromedioAcademico();
                $pregunta['parametros_promedio_conducta']  = $this->listaItemParametrosAdicionalDosItems($pregunta->id);
                //getParametrosPromedioConducta();
            }

        }

        $encuesta = $proyectoCliente->encuesta;
        $encuesta['estudio'] = $elemento;
        $encuesta['proyecto'] = $proyectoCliente->proyecto;
        $encuesta['imagenes'] = $this->listaDeDocumentosEstudio($id);
        //$encuesta['cliente'] = $elemento->cliente;
        if($id_hijo){
            $encuesta['hijo'] = ServiciosEstudiosRespuestas::where('id', $id_hijo)->first();
        }

        $parametros = CatalogoEncuestasPreguntasParametrosClasificacions::where('id_catalogo_encuesta', $encuesta->id)->get();

        foreach ($parametros as &$parametro) {
            $parametro['puntos'] = $this->puntosPrecuntaSeccion($parametro, $id, $encuesta->preguntas,$id_hijo);
        }
        $encuesta['parametros'] = $parametros;
        $encuesta['total_parametros'] = $this->totalPorParametro($encuesta->preguntas);

        //return $encuesta;

        // Cargar la vista y pasar los datos
        return $encuesta;
    }

    public function resultadosEstudiosSocieconomicosReporte(Request $request){
        $os = $request->ordenes_servicio;
        //$elementos = ServicioEstudio::with(['cliente'])->whereIn('id', $request->ordenes_servicio)->get();
        $datos = [];
        foreach($os as $elemento){
          array_push($datos, $this->generateDatosEncuestasEstudioSocioeconomicoPDF($elemento));
        }


        return response(["message"=> "llego", "datos" => $datos]);
    }

    public function estudioSocioeconomicoPDF(Request $request,$id)
    {
        if (!$id) {
            return response()->json([
                "errors" => [
                    'estudio' => ['No se recibió estudio'],
                ]
            ], 400);
        }

        $id_hijo = 0;
        if(isset($request->id_hijo)){
            $id_hijo = $request->id_hijo;
        }

        $pdf = $this->generateEstudioSocioeconomicoPDF($id,$id_hijo);
        // Descargar el archivo PDF
        return $pdf->download("Estudio_{$id}_{$id_hijo}.pdf");
    }

    public function estudioSocioeconomicoRangos($id_estudio)
    {
        $rango_pordentaje = [
            ["rango" => 20, "nombre" => 'de 0 a 20%',    "porcentaje" => 5],
            ["rango" => 40, "nombre" => 'de 20 a 40%',   "porcentaje" => 10],
            ["rango" => 60, "nombre" => 'de 40 a 60%',   "porcentaje" => 15],
            ["rango" => 80, "nombre" => 'de 60 a 80%',   "porcentaje" => 20],
            ["rango" => 100, "nombre" => 'de 80 a 100%',  "porcentaje" => 25]
        ];
        return response()->json($rango_pordentaje);
    }
    public function estudioSocioeconomicoProcentaje(Request $request, $id_estudio)
    {
        $elemento = array();

        if(isset($request->id_hijo)){
            $elemento = ServiciosEstudiosRespuestas::where('id', $request->id_hijo)->where('id_servicio_estudio', $id_estudio)->first();
        }else{
            $elemento = ServicioEstudio::where('id', $id_estudio)->first();
        }


        if (!$elemento) {
            return response()->json(["errors" => "Elemento no exsiste"], 404);
        }

        $elemento->porcentaje_otorgado = $request->porcentaje_otorgado;
        $elemento->save();

        return response()->json(['message' => 'Elemento actualizado', 'data' => $elemento], 200);
    }

    public function estudioSocioeconomicoClaveFamilia(Request $request, $id_estudio)
    {

        $elemento = ServicioEstudio::where('id', $id_estudio)->first();

        if (!$elemento) {
            return response()->json(["errors" => "Elemento no exsiste"], 404);
        }

        $elemento->clave_familia_colegio = $request->clave_familia_colegio;
        $elemento->save();

        return response()->json(['message' => 'Elemento actualizado', 'data' => $elemento], 200);
    }

    public function estudioSocioeconomicoDownloadZip(Request $request)
    {

        $messages = [
            'lista_encuestas.array' => 'Seleccione un estudio.',
        ];

        $validator = Validator::make(
            $request->all(),
            [
                'lista_encuestas' => 'required|array',
            ],
            $messages
        );

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }

        $dataSets =  $request->lista_encuestas;

        $id_encuesta = $dataSets[0];

        $elemento = ServicioEstudio::with('cliente')->where('id', $id_encuesta)->first();
        $dir_nombre = $elemento->cliente->nombre;
        $dir_id = $elemento->cliente->id;


        // Crear una carpeta temporal para almacenar los PDFs
        $tempFolder = storage_path('app/temp_pdfs/' . $dir_id . $dir_nombre);
        if (!is_dir($tempFolder)) {
            mkdir($tempFolder, 0755, true);
        }

        // Generar cada PDF y guardarlo en la carpeta temporal
        foreach ($dataSets as  $id) {
            $id_hijo = 0;
            if(isset($dataSets->id_hijo)){
                $id_hijo = $dataSets->id_hijo;
            }
            $pdf = $this->generateEstudioSocioeconomicoPDF($id,$id_hijo);
            $filePath = $tempFolder . "/file_{$id}.pdf";
            $pdf->save($filePath);
        }

        // Crear el archivo ZIP
        $zipPath = storage_path('app/public/' . $dir_nombre . '.zip'); // Ruta del ZIP a generar
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach (glob($tempFolder . '/*.pdf') as $pdfFile) {
                $zip->addFile($pdfFile, basename($pdfFile));
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'No se pudo crear el archivo ZIP'], 500);
        }

        // Eliminar los archivos temporales
        array_map('unlink', glob($tempFolder . '/*.pdf'));
        rmdir($tempFolder);

        // Retornar el archivo ZIP como respuesta
        return response()->download($zipPath, $dir_nombre . '.zip', [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $dir_nombre . '.zip"',
        ])->deleteFileAfterSend(true);
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function parametroAdicionalUnoItems($id_pregunta){
        $id_parametro = CatalogoEncuestasPreguntas::where('id', $id_pregunta)->first()->id_parametro_clasificacion_parametro_adicional_uno;

        if(!$id_parametro){
            return response()->json([], 200);
        }
        $preguntaItem = CatalogoEncuestasPreguntasParametrosClasificacionItems::where('id_catalogo_encuestas_preguntas_parametro_clasificacion',$id_parametro)->get();

        return response()->json($preguntaItem, 200);
    }
    public function parametroAdicionalDosItems($id_pregunta){
        $id_parametro = CatalogoEncuestasPreguntas::where('id', $id_pregunta)->first()->id_parametro_clasificacion_parametro_adicional_dos;

        if(!$id_parametro){
            return response()->json([], 200);
        }
        $preguntaItem = CatalogoEncuestasPreguntasParametrosClasificacionItems::where('id_catalogo_encuestas_preguntas_parametro_clasificacion',$id_parametro)->get();

        return response()->json($preguntaItem, 200);
    }
    public function getSumatoruaB($id_estudio,$id_pregunta){

        $id_parametro = CatalogoEncuestasPreguntas::where('id', $id_pregunta)->first()->id_catalogo_encuestas_preguntas_parametro_clasificacion;
        //where('id', $id_pregunta)->
            return response()->json($id_pregunta, 200);
        if(!$id_parametro){
            return response()->json(0, 200);
        }

        $lista_preguntas = CatalogoEncuestasPreguntas::where('id_catalogo_encuestas_preguntas_parametro_clasificacion', $id_parametro)->get()->id;

        return response()->json($lista_preguntas, 200);

        $lista_respuestas = ServiciosEstudiosRespuestas::where('id_servicio_estudio', $elemento->id)->where('id_catalogo_encuestas_pregunta', $pregunta->id)->get();
        $preguntaItem = CatalogoEncuestasPreguntasParametrosClasificacionItems::where('id_catalogo_encuestas_preguntas_parametro_clasificacion',$id_parametro)->get();

        return response()->json($preguntaItem, 200);
    }


}
