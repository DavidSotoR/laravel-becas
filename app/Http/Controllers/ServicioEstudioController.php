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
use App\CatalogoEncuestasPreguntasParametrosClasificacionItems;
use App\Mail\NotificacionCorreo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;

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

    public function lista(Request $request){
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

        if(isset($request->id_proyecto)){

            $query->where('id_proyecto',$request->id_proyecto);

            if(isset($request->id_cliente)){
                if($request->id_cliente)
                $query->where('id_cliente',$request->id_cliente);

                if(isset($request->id_orden_servicio)){
                    if($request->id_orden_servicio)
                    $query->where('id_orden_servicio',$request->id_orden_servicio);
                }
            }
        }

        if(isset($request->id_colaborador)){
            if($request->id_colaborador)
            $query->where('id_colaborador',$request->id_colaborador);
        }

        $lista = $query->get();
        return response()->json($lista);
    }

    public function listaEnProceso(Request $request){
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
        switch($id_perfil){
            case 1:
                //Administrador
                $query->where('id_colaborador',$user->id);
                break;
            case 2:
                //Gerencia
                $query->where('id_colaborador',$user->id);
                break;
            case 3:
                //Calidad
                $query->where('id_colaborador',$user->id);
                break;
            case 4:
                //Colaboradores
                $query->where('id_colaborador',$user->id);
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

        if(isset($request->id_proyecto)){

            $query->where('id_proyecto',$request->id_proyecto);

            if(isset($request->id_cliente)){
                if($request->id_cliente)
                $query->where('id_cliente',$request->id_cliente);

                if(isset($request->id_orden_servicio)){
                    if($request->id_orden_servicio)
                    $query->where('id_orden_servicio',$request->id_orden_servicio);
                }
            }
        }
        $query->where('id_servicio_estado','!=',1);
        if(isset($request->id_servicio_estado)){
            $query->where('id_servicio_estado',$request->id_servicio_estado);
        }

        $lista = $query->get();
        return response()->json($lista);
    }
    public function listaConcluidos(Request $request,int $id_proyecto){

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
        ]);

        //$query->where('id_colaborador',$user->id);
        switch($id_perfil){
            //Administrador
            case 1:
            //Gerencia
            case 2:
            //Calidad
            case 3:
            //Colaboradores
            case 4:

                if(isset($request->id_cliente)){
                    if($request->id_cliente)
                    $query->where('id_cliente',$request->id_cliente);
                }

                $query->where('id_colaborador',$user->id);


                $query->where('id_servicio_estado','!=',1);
                if(isset($request->id_servicio_estado)){
                    $query->where('id_servicio_estado',$request->id_servicio_estado);
                }

                break;
            case 5:
                //Empresas
                $id_cliente = $user->id_cliente;
                $query->where('id_cliente',$id_cliente);

                $query->where('id_servicio_estado','!=',1);

                break;
            case 6:
                //Familias
                return response()->json([]);
                break;
            default:
            return response()->json([]);
        }



        $query->where('id_proyecto',$id_proyecto);


        if(isset($request->id_orden_servicio)){
            if($request->id_orden_servicio)
            $query->where('id_orden_servicio',$request->id_orden_servicio);
        }

        $lista = $query->get();

        /*$proyectoCliente = ProyectosClientes::
                            with(['encuesta','encuesta.preguntas','proyecto'])
                            ->where('id_proyecto',$id_proyecto)
                            ->where('id_cliente',$user->id_cliente)
                            ->first();
        $encuesta = $proyectoCliente->encuesta;*/


        foreach($lista AS &$estudio){
            /*$parametros = CatalogoEncuestasPreguntasParametrosClasificacions::where('id_catalogo_encuesta',$encuesta->id)->get();

            foreach($parametros AS &$parametro){
                $parametro['puntos'] = $this -> puntosPrecuntaSeccion($parametro,$estudio->id,$encuesta->preguntas);
            }
            */
            $parametros = $this->estudioParametrosPuntos($estudio->id);
            $estudio['parametros'] = $parametros;
        }


        return response()->json($lista);
    }

    public function id($id){
        $elemento = ServicioEstudio::with([
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
        ])->where('id',$id)->first();

        $proyectoCliente = ProyectosClientes::with('encuesta')->where('id_proyecto',$elemento->id_proyecto)->where('id_cliente',$elemento->id_cliente)->first();

        //$elemento['proyecto_cliente'] = $proyectoCliente;
        $encuesta = $proyectoCliente->encuesta;

        $elemento['encuesta'] = $encuesta;

        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
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

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        $elemento = ServicioEstudio::create($validator->validate());

        $servicio_estudio = $elemento->id;
        $colegios_comunes= $request->colegios_comunes;

        if(isset($colegios_comunes) AND isset($servicio_estudio)){
            foreach($colegios_comunes AS $id_colegio_comun){
                ServiciosEstudiosClientesComunes::create(['id_servicio_estudio'=>$servicio_estudio,'id_cliente'=>$id_colegio_comun]);
            }
        }

        return response()->json(['message' => 'Nuevo elemento creado', 'data' => $elemento], 201);
    }

    public function cargaMasivaFamilias(Request $request){
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file'); // Obtener el archivo
        $data = [];

        // Abrir el archivo en modo lectura
        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            $headers = fgetcsv($handle); // Leer la primera fila como encabezados

            while (($row = fgetcsv($handle)) !== false) {
                $data[] = array_combine($headers, $row); // Combinar encabezados con valores
            }

            fclose($handle);
        }

        // Devolver el array procesado como respuesta JSON (para pruebas)
        return response()->json(['data' => $data]);
    }

    public function editar(Request $request,$id){

        $servicio = ServicioEstudio::findOrFail($id);

        $validator = Validator::make($request->all(),[
            'id_servicio_estado' => 'required|exists:servicio_estados,id',
            'id_orden_servicio' => 'required|exists:ordenes_servicio,id',
            'id_familia' => 'required|exists:familias,id',
            'id_cliente' => 'required|exists:clientes,id',
            'id_colaborador' => 'null|exists:users,id',
            'es_familia_comun' => 'null|boolean',
        ]);

        if($validator->fails()){
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


    public function rejistroSocioeconomico(Request $request){

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


        $validator = Validator::make($request->all(),[
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
            'padre' => 'required|array',
            'padre.id_familias_padres_tipo' => 'required|integer|exists:familias_padres_tipos,id',
            'padre.nombre' => 'required|string|max:255',
            'padre.edad' => 'required|integer|min:0',
            'padre.vive' => 'required|boolean',
            'padre.direccion' => 'required|string|max:255',
            'padre.ocupacion_actual' => 'nullable|string|max:255',
            'padre.empresa_trabajo' => 'nullable|string|max:255',
            'padre.email' => ['required','email:rfc','max:255','regex:/^\S*$/u'],
            'padre.telefono_casa' => 'required|string|max:15',
            'padre.contecto_principal' => 'required|boolean',

            //Validar datos de madre
            'madre' => 'required|array',
            'madre.id_familias_padres_tipo' => 'required|integer|exists:familias_padres_tipos,id',
            'madre.nombre' => 'required|string|max:255',
            'madre.edad' => 'required|integer|min:0',
            'madre.vive' => 'required|boolean',
            'madre.direccion' => 'required|string|max:255',
            'madre.ocupacion_actual' => 'nullable|string|max:255',
            'madre.empresa_trabajo' => 'nullable|string|max:255',
            'madre.email' => ['required','email:rfc','max:255','regex:/^\S*$/u'],
            'madre.telefono_casa' => 'required|string|max:15',
            'madre.contecto_principal' => 'required|boolean',

        ],$messages);


        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        if($request->padre["contecto_principal"]){
            $contacto_por_defecto = $request->padre;
            $contacto_por_defecto_es = 'padre';
        } else if($request->madre["contecto_principal"]){
            $contacto_por_defecto = $request->madre;
            $contacto_por_defecto_es = 'madre';
        }else{
            return response()->json([
                "errors"=>[
                    'padre.contecto_principal' => ['Seleccione un contacto principal'],
                    'madre.contecto_principal' => ['Seleccione un contacto principal'],
                    ]
            ], 400);
        }
        //validar si ya esisite un contacot con en la orden de servicio con el mismo email
        $estudio_contacto = ServicioEstudio::with(['familiasPadres' => function($query) use ($contacto_por_defecto){
            $query
                ->where('email',$contacto_por_defecto["email"]);
                //->where('contecto_principal',true);
        }])->where('id_orden_servicio',$request->id_orden_servicio)->first();

        /* if($estudio_contacto && count($estudio_contacto->familias_padres)){
            return response()->json([
                "errors"=>[
                    $contacto_por_defecto_es.'.contecto_principal' => ['Contacto principal ya registrado en esta orden de servicio'],
                    ]
            ], 400);
        }
 */

        $user = User::where('email',$contacto_por_defecto["email"])->first();

        if($user){
            $id_familia = $user->id;
        }else{
            if($request->generar_usuario_automaticamente == true){
                $password_temposral =  $this -> generarContraseñaTemporal();

                $usuario_familia = User::create([
                        'name' => $contacto_por_defecto["nombre"]
                        ,'email' => $contacto_por_defecto["email"]
                        ,'id_cliente' => $request->id_cliente
                        ,'id_perfil' => 6
                        ,'password' => bcrypt($password_temposral)
                        ,'password_temporal' => $password_temposral
                        ,'externo'=> 1
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
        $colegios_comunes= $request->colegios_comunes;

        $padre = array();
        $madre = array();


        if(isset($colegios_comunes) AND isset($id_servicio_estudio)){
            foreach($colegios_comunes AS $id_colegio_comun){
                ServiciosEstudiosClientesComunes::create(['id_servicio_estudio'=>$id_servicio_estudio,'id_cliente'=>$id_colegio_comun]);
            }

            $padre_request = $request->padre;
            $madre_request = $request->madre;

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
        if($directorio != ''){
            $elemento['directorio'] = $directorio;
        }

        return response()->json(['message' => 'Nuevo elemento creado', 'data' => $elemento], 201);
    }


    private function generarContraseñaTemporal(){
        $dataSetCaracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $mesclar = str_shuffle($dataSetCaracteres);
        $nuevaContraseña = substr($mesclar,0,8);
        return $nuevaContraseña;
    }

    private function setDirectorioEstudio($id){
        $directorio = '';

        $editar = ServicioEstudio::where('id',$id)->first();
        if(!$editar){
            return '';
        }

        $proyecto = Proyectos::where('id',$editar->id_proyecto)->first();
        $directorio .= $this->limpiarCadena($proyecto->nombre);
        $ordenServicio = OrdenesServicio::where('id',$editar->id_orden_servicio)->first();
        $directorio .= "/".$ordenServicio->id.'_'.$this->limpiarCadena($ordenServicio->descripcion);
        $directorio .= "/".$id.'_'.$this->limpiarCadena($editar->candidato) ;


        $editar->directorio = $directorio."/";
        $editar->save();

        return $directorio;
    }

    function limpiarCadena($cadena) {
        // Convertir los espacios en guiones bajos
        $cadena = str_replace(' ', '_', $cadena);

        // Eliminar todos los caracteres que no sean letras o números (quitar caracteres especiales)
        $cadena = preg_replace('/[^a-zA-Z0-9_]/', '', $cadena);

        return $cadena;
    }

    public function asignarColaborador($id_estudio = 0,Request $request){
        if(!$id_estudio){
            return response()->json([
                "errors"=>[
                    'estudio' => ['No se recibió estudio'],
                    ]
            ], 400);
        }

        $validator = Validator::make($request->all(),[
            'id_colaborador' => 'required|exists:user,id',
            ]
        );

        $elemento = ServicioEstudio::where('id',$id_estudio)->first();
        $elemento->id_colaborador = $request->id_colaborador;
        $elemento->save();

        return response()->json(['message' => 'Elemento guardado', 'data' => $elemento], 201);
    }

    public function preasignarEstudios(Request $request){

        $messages = [
            'id_servicios_estudio.array' => 'Seleccione un estudio.',
            'id_colaborador.required' => 'Seleccione un colaborador.',
            'id_colaborador.exists' => 'El colaborador no exsiste.',
        ];

        $validator = Validator::make($request->all(),[
            'id_colaborador' => 'required|exists:user,id',
            'id_servicios_estudio' => 'required|array',
            ]
        ,$messages);

        $elemento = ServicioEstudio::
                        whereIn('id', $request->id_servicios_estudio)
                        //->whereNotNull('id_servicio_estado', 2)
                        ->update(['id_servicio_estado' => 2,'id_colaborador' => $request->id_colaborador]);


        return response()->json(['message' => 'Elemento guardado', 'data' => $elemento], 201);
    }
    public function asignarEstudios(Request $request){

        $messages = [
            'id_servicios_estudio.array' => 'Seleccione un estudio.',
        ];

        $validator = Validator::make($request->all(),[
            'id_servicios_estudio' => 'required|array',
            ]
        ,$messages);

        $total = 0;
        $total = count($request->id_servicios_estudio);

        $elementos = ServicioEstudio::
            whereIn('id', $request->id_servicios_estudio)
            ->whereNotNull('id_colaborador')
            ->update(['id_servicio_estado' => 2]);

        $message = 'Elemenost asignados';
        if($total != $elementos){
            $message = 'Elemenost asignados '.$elementos.' de '.$total.', '.($total-$elementos).' sin colaborador asignado';
        }

        return response()->json(['message' => $message, 'data' => $elementos], 201);
    }
    public function asignarCalidad(Request $request){

        $messages = [
            'id_servicios_estudio.array' => 'Seleccione un estudio.',
            'id_calidad.required' => 'Seleccione un colaborador.',
            'id_calidad.exists' => 'El colaborador no exsiste.',
        ];

        $validator = Validator::make($request->all(),[
            'id_calidad' => 'required|exists:user,id',
            'id_servicios_estudio' => 'required|array',
            ]
        ,$messages);

        $total = 0;
        $total = count($request->id_servicios_estudio);

        $elementos = ServicioEstudio::
            whereIn('id', $request->id_servicios_estudio)
            ->whereNotNull('id_servicio_estado', 2)
            ->update(['id_servicio_estado' => 3,'id_calidad' => $request->id_calidad]);

        $message = 'Elementos enviados a calidad';
        if($total != $elementos){
            $message = 'Elementos enviados a calidad '.$elementos.' de '.$total.', '.($total-$elementos).' aun requieren infomracion';
        }

        return response()->json(['message' => $message, 'data' => $elementos], 201);
    }
    public function encuesta($id_estudio = 0){
        if(!$id_estudio){
            return response()->json([], 201);
        }

        $estudio = ServicioEstudio::where('id',$id_estudio)->first();


        $id_proyecto = $estudio->id_proyecto;
        $id_cliente = $estudio->id_cliente;

        $preoyecto_cliente = ProyectosClientes::where('id_proyecto',$id_proyecto)->where('id_cliente',$id_cliente)->first();
        $id_encuesta = $preoyecto_cliente->id_encuesta;

        $encuesta = CatalogoEncuestas::where('id',$id_encuesta)->first();

        return response()->json($encuesta);
    }

    public function addFechaVisita(Request $request, $id = 0){

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

        if(!$solicitud){
            return response()->json([
                "errors"=>[
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

    public function estudioSocioeconomico($id){

        /*with(['estado','cliente','proyecto','ordenServicio','colaborador','colegiosComunes',])->*/
        //$elemento['encuesta'] = $encuesta;

        $elemento = ServicioEstudio::with(['cliente'])->where('id',$id)->first();

        $proyectoCliente = ProyectosClientes::with(['encuesta','encuesta.preguntas','proyecto'])->where('id_proyecto',$elemento->id_proyecto)->where('id_cliente',$elemento->id_cliente)->first();

        foreach($proyectoCliente->encuesta->preguntas AS &$pregunta){
            $pregunta['respuestas'] = ServiciosEstudiosRespuestas::where('id_servicio_estudio',$elemento->id)->where('id_catalogo_encuestas_pregunta',$pregunta->id)->get();
        }

        $encuesta = $proyectoCliente->encuesta;
        $encuesta['estudio'] = $elemento;
        $encuesta['proyecto'] = $proyectoCliente->proyecto;
        //$encuesta['cliente'] = $elemento->cliente;

        $parametros = CatalogoEncuestasPreguntasParametrosClasificacions::where('id_catalogo_encuesta',$encuesta->id)->get();

        foreach($parametros AS &$parametro){
            $parametro['puntos'] = $this -> puntosPrecuntaSeccion($parametro,$id,$encuesta->preguntas);
        }
        $encuesta['parametros'] = $parametros;



        return response()->json($encuesta);
    }

    public function estudioSocioeconomicoParametrosPuntos($id){

        $elemento = ServicioEstudio::where('id',$id)->first();

        $proyectoCliente = ProyectosClientes::with(['encuesta.preguntas'])->where('id_proyecto',$elemento->id_proyecto)->where('id_cliente',$elemento->id_cliente)->first();

        $encuesta = $proyectoCliente->encuesta;

        $parametros = CatalogoEncuestasPreguntasParametrosClasificacions::where('id_catalogo_encuesta',$encuesta->id)->get();

        foreach($parametros AS &$parametro){
            $parametro['puntos'] = $this -> puntosPrecuntaSeccion($parametro,$id,$encuesta->preguntas);
        }

        //$parametros;

        return response()->json($parametros);
    }

    public function estudioParametrosPuntos($id){

        $elemento = ServicioEstudio::where('id',$id)->first();

        $proyectoCliente = ProyectosClientes::with(['encuesta.preguntas'])->where('id_proyecto',$elemento->id_proyecto)->where('id_cliente',$elemento->id_cliente)->first();

        $encuesta = $proyectoCliente->encuesta;

        $parametros = CatalogoEncuestasPreguntasParametrosClasificacions::where('id_catalogo_encuesta',$encuesta->id)->get();

        foreach($parametros AS &$parametro){
            $parametro['puntos'] = $this -> puntosPrecuntaSeccion($parametro,$id,$encuesta->preguntas);
        }

        //$parametros;

        return $parametros;
    }

    private function puntosPrecuntaSeccion($parametro,$id_estudio,$preguntas){

        $puntos = 0;

        $lista_respuestas = array();

        foreach($preguntas AS $pregunta){

            if($parametro->id == $pregunta->id_catalogo_encuestas_preguntas_parametro_clasificacion){

                $respuestas = ServiciosEstudiosRespuestas::
                                where('id_servicio_estudio',$id_estudio)
                                ->where('id_catalogo_encuestas_pregunta',$pregunta->id)
                                ->get();

                $respuestas_array = $respuestas->toArray();

                if(!count($lista_respuestas)){
                    $lista_respuestas = $respuestas_array;
                }else{
                    if(count($respuestas_array)){
                        $lista_respuestas = array_merge($lista_respuestas, $respuestas_array);
                    }
                }
                //$respuestas;//$this -> calculoDePuntosPorTipo($pregunta,$respuestas);
                //$lista_respuestas[] = ["id_servicio_estudio"=>$id_estudio,"id_catalogo_encuestas_pregunta"=>$pregunta->id,'respuestas'=>$respuestas_array]; // $this -> sumatoriaRespuesta($respuestas);

            }



        }

        //return $lista_respuestas;

        $sumatorias_por_seccion = $this -> sumatoriaRespuesta($lista_respuestas);
        $total = 0;

        switch($parametro->id_catalogo_encuestas_preguntas_parametros_clasificaciones_tipos){
            case 1:
                foreach($sumatorias_por_seccion AS $seccion){
                    $total += $seccion['padre_monto'];
                    $total += $seccion['madre_monto'];
                    $total += $seccion['monto'];
                }
            break;
            default:
                $puntos = null;
            break;
        }
        $puntos = $this -> obtenerRango($parametro->id,$total);
        $puntos["sumatoria"] = $total;
        //$items = CatalogoEncuestasPreguntasParametrosClasificacionItems::where('id_catalogo_encuestas_preguntas_parametro_clasificacion',$parametro->id)->get();

        return $puntos;

    }

    private function sumatoriaRespuesta($respuestas){
        $resultado = [];

        foreach ($respuestas as $item) {
            // Si la sección es null, lo ignoramos o puedes manejarlo de otra forma
            if ($item['seccion'] === null) {
                $item['seccion'] ="";
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
    function obtenerRango($idParametros,$puntos) {
        return CatalogoEncuestasPreguntasParametrosClasificacionItems::where(function($query) use ($puntos) {
            $query->where(function($q) use ($puntos) {
                // Caso 1: Limite inferior es 0 (todos los menores a limite superior)
                $q->where('limiten_inferior', 0)
                  ->where('limite_superior', '>', $puntos);
            })
            ->orWhere(function($q) use ($puntos) {
                // Caso 2: Limite superior es 0 (todos los mayores a limite inferior)
                $q->where('limite_superior', 0)
                  ->where('limiten_inferior', '<', $puntos);
            })
            ->orWhere(function($q) use ($puntos) {
                // Caso 3: Rango entre limite inferior y limite superior
                $q->where('limiten_inferior', '<=', $puntos)
                  ->where('limite_superior', '>=', $puntos);
            });
        })
        ->where('id_catalogo_encuestas_preguntas_parametro_clasificacion',$idParametros)->first(); // Devolvemos el primer resultado que coincida
    }


    /*private function calculoDePuntosPorTipo($idPreguntaTipo,$respuestas){

    }*/

    public function estudioSocioeconomicoPDF($id){
        if(!$id){
            return response()->json([
                "errors"=>[
                    'estudio' => ['No se recibió estudio'],
                    ]
            ], 400);
        }

        // Obtener los datos
        $elemento = ServicioEstudio::with(['cliente'])->where('id',$id)->first();

        $proyectoCliente = ProyectosClientes::with(['encuesta','encuesta.preguntas','proyecto'])->where('id_proyecto',$elemento->id_proyecto)->where('id_cliente',$elemento->id_cliente)->first();

        foreach($proyectoCliente->encuesta->preguntas AS &$pregunta){
            $pregunta['respuestas'] = ServiciosEstudiosRespuestas::where('id_servicio_estudio',$elemento->id)->where('id_catalogo_encuestas_pregunta',$pregunta->id)->get();
        }

        $encuesta = $proyectoCliente->encuesta;
        $encuesta['estudio'] = $elemento;
        $encuesta['proyecto'] = $proyectoCliente->proyecto;
        //$encuesta['cliente'] = $elemento->cliente;

        $parametros = CatalogoEncuestasPreguntasParametrosClasificacions::where('id_catalogo_encuesta',$encuesta->id)->get();

        foreach($parametros AS &$parametro){
            $parametro['puntos'] = $this -> puntosPrecuntaSeccion($parametro,$id,$encuesta->preguntas);
        }
        $encuesta['parametros'] = $parametros;

        // Cargar la vista y pasar los datos
        $pdf = PDF::loadView('pdf.estudio_socioeconomico', compact('encuesta'))->setPaper('A4', 'portrait');

        // Descargar el archivo PDF
        return $pdf->download("Estudio_{$id}.pdf");

    }

    public function estudioSocioeconomicoRangos($id_estudio){
        $rango_pordentaje =[
            ["rango" => 20, "nombre" => 'de 0 a 20%',    "porcentaje"=> 5],
            ["rango" => 40, "nombre" => 'de 20 a 40%',   "porcentaje"=> 10],
            ["rango" => 60, "nombre" => 'de 40 a 60%',   "porcentaje"=> 15],
            ["rango" => 80, "nombre" => 'de 60 a 80%',   "porcentaje"=> 20],
            ["rango" => 100,"nombre" => 'de 80 a 100%',  "porcentaje"=> 25]
        ];
        return response()->json($rango_pordentaje);
    }
    public function estudioSocioeconomicoProcentaje(Request $request,$id_estudio){

        $elemento = ServicioEstudio::where('id',$id_estudio)->first();

        if(!$elemento){
            return response()->json(["errors"=>"Elemento no exsiste"], 404);
        }

        $elemento->porcentaje_otorgado = $request->porcentaje_otorgado;
        $elemento->save();

        return response()->json(['message' => 'Elemento actualizado', 'data' => $elemento], 200);
    }

    public function estudioSocioeconomicoClaveFamilia(Request $request,$id_estudio){

        $elemento = ServicioEstudio::where('id',$id_estudio)->first();

        if(!$elemento){
            return response()->json(["errors"=>"Elemento no exsiste"], 404);
        }

        $elemento->clave_familia_colegio = $request->clave_familia_colegio;
        $elemento->save();

        return response()->json(['message' => 'Elemento actualizado', 'data' => $elemento], 200);
    }
}
