<?php

namespace App\Http\Controllers;

use App\ServicioEstudio;
use App\ServiciosEstudiosClientesComunes;
use App\Proyectos;
use App\FamiliasPadres;

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
        $query = ServicioEstudio::query();

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
        $elemento = ServicioEstudio::where('id',$id)->first();
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

        $messages = [
            'candidato.required' => 'El nombrede familia es requerido.',
            'padre.nombre.required' => 'El nombre del padre es requerido.',
            'madre.nombre.required' => 'El nombre del madre es requerido.'
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

            //Validar datos de padrre
            'padre' => 'required|array',
            'padre.id_familias_padres_tipo' => 'required|integer|exists:familias_padres_tipos,id',
            'padre.nombre' => 'required|string|max:255',
            'padre.edad' => 'required|integer|min:0',
            'padre.vive' => 'required|boolean',
            'padre.direccion' => 'required|string|max:255',
            'padre.ocupacion_actual' => 'nullable|string|max:255',
            'padre.empresa_trabajo' => 'nullable|string|max:255',
            'padre.email' => 'nullable|email|max:255',
            'padre.telefono_casa' => 'nullable|string|max:15',
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
            'madre.email' => 'nullable|email|max:255',
            'madre.telefono_casa' => 'nullable|string|max:15',
            'madre.contecto_principal' => 'required|boolean',

        ],$messages);
        //FamiliasPadres

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        $elemento = ServicioEstudio::create($validator->validate());

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

        return response()->json(['message' => 'Nuevo elemento creado', 'data' => $elemento], 201);
    }
}
