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

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

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

        if($estudio_contacto){
            return response()->json([
                "errors"=>[
                    $contacto_por_defecto_es.'.contecto_principal' => ['Contacto principal ya registrado en esta orden de servicio'],
                    ]
            ], 400);
        }


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
                ]);

                $id_familia =  $usuario_familia->id;
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

    public function asignarColaboradores(Request $request){

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

        $elemento = ServicioEstudio::whereIn('id', $request->id_servicios_estudio)->update(['id_colaborador' => $request->id_colaborador]);

        return response()->json(['message' => 'Elemento guardado', 'data' => $elemento], 201);
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
}
