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
use Illuminate\Http\Request;

class EstudiosSocioeconomicosReportesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    private function estudiosPorProyectoCliente($id_proyecto,$id_cliente,$id_orden_servicio = 0){

        $query = ServicioEstudio::query()->with([
            'estado',
            'cliente',
            'proyecto',
            'ordenServicio',
            'colaborador',
        ]);
        /*
            'padre',
            'madre',
            'contactoPrincipal',
         */

        //$user = auth()->user();
        //$id_perfil = $user->perfil->id;

        $query->where('id_proyecto', $id_proyecto);
        //$id_cliente = $user->id_cliente;
        $query->where('id_cliente', $id_cliente);
        if($id_orden_servicio){
            $query->where('id_orden_servicio', $id_orden_servicio);
        }

        $query->where('id_servicio_estado', '!=', 1);


        $lista = $query->get();

        return $lista;
    }

    public function distribucionDelGasto(Request $request, int $id_proyecto){

        if(!$id_proyecto){
            return response()->json(["errors" => ["id_proyecto" => ["Proyecto no recibido"]]], 400);
        }

        $user = auth()->user();
        $id_cliente = $user->id_cliente;

        $id_orden_servicio = 0;

        if (isset($request->id_orden_servicio)) {
            $id_orden_servicio = $request->id_orden_servicio;
        }

        $lista = $this->estudiosPorProyectoCliente($id_proyecto,$id_cliente,$id_orden_servicio);

        foreach ($lista as &$estudio) {

            //$parametros = $this->estudioParametrosPuntos($estudio->id,0);
            //$estudio['parametros'] = $parametros;

            $estudio['distribucion_del_gasto'] = $this->getDistribucionDelGasto(
                $estudio->id_proyecto,
                $estudio->id_cliente,
                $estudio->id
            );
        }

        return response()->json($lista);
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
            $grantotal += $sumatoria;
            $lista_totales[] = [ "categoria" => $clasificacion->nombre, "total" => $sumatoria ] ;
        }

        return $lista_totales;
    }
    //gastosPorRangoIngresoMensual

    public function gastosPorRangoIngresoMensual(Request $request, int $id_proyecto){

        if(!$id_proyecto){
            return response()->json(["errors" => ["id_proyecto" => ["Proyecto no recibido"]]], 400);
        }

        $user = auth()->user();
        $id_cliente = $user->id_cliente;

        $id_orden_servicio = 0;

        if (isset($request->id_orden_servicio)) {
            $id_orden_servicio = $request->id_orden_servicio;
        }

        $lista = $this->estudiosPorProyectoCliente($id_proyecto,$id_cliente,$id_orden_servicio);

        foreach ($lista as &$estudio) {

            $parametros = $this->estudioParametrosPuntos($estudio->id,0);
            $estudio['parametros'] = $parametros;

        }

        return response()->json($lista);
    }
}
