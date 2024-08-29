<?php

namespace App\Http\Controllers;

use App\ServicioEstudio;
use App\ServiciosEstudiosClientesComunes;
use App\Proyectos;

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
            //if($request->id_proyecto)
            //$query->where('id_proyecto',$request->id_proyecto);

            $query->where('id_cliente',$request->id_proyecto);

            if(isset($request->id_cliente)){
                if($request->id_cliente)
                $query->where('id_cliente',$request->id_cliente);

                if(isset($request->id_orden_servicio)){
                    if($request->id_orden_servicio)
                    $query->where('id_orden_servicio',$request->id_orden_servicio);
                }
            }/*else{
                //Proyectos::when('clientes')->where('id',$request->id_proyecto)->get()
                /*$clientesIds = Proyectos::when($request->has('clientes'), function ($query) use ($request) {
                    return $query->where('id', $request->id_proyecto);
                })->pluck('id_cliente')->toArray();
                $query->whereIn('id_cliente',$clientesIds);
            }*/
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
}
