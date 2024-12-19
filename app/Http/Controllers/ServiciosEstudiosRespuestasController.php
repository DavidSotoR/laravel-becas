<?php

namespace App\Http\Controllers;

use App\ServiciosEstudiosRespuestas;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ServiciosEstudiosRespuestasController extends Controller
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

    public function lista($id_estudio = 0,$id_pregunta = 0){

        $query = ServiciosEstudiosRespuestas::query();

        $query->where('id_servicio_estudio',$id_estudio);
        $query->where('id_catalogo_encuestas_pregunta',$id_pregunta);

        $lista = $query->get();
        return response()->json($lista);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'id_catalogo_encuestas_preguntas_tipo' => 'required|exists:catalogo_encuestas_preguntas_tipos,id',
            'respuestas' => 'required|array',
        ]);
        //FamiliasPadres

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        switch($request->id_catalogo_encuestas_preguntas_tipo){
            case 1:
            case 3:
            case 4:
            case 5:
            case 9:
            case 10:
                return $this -> respuestaTipoUno($request->respuestas);
            break;
            case 11:
            case 12:
            case 13:
            case 6:
                return $this -> respuestaTipoSeis($request->respuestas);
            break;
            case 20:
            case 7:
                return $this -> respuestaTipoSiete($request->respuestas);
            break;
            case 14:
            case 15:
            case 8:
                return $this -> respuestaTipoOcho($request->respuestas);
            break;

            default:
                return response()->json([
                    "errors"=>[ 'id_catalogo_encuestas_preguntas_tipo'=> ['Tipo de pregunta no manejada'] ]
                ], 400);
            break;

        }

        return response()->json(['message' => 'Nuevo elemento creado', 'data' => $elemento], 201);
    }

    private function respuestaTipoUno($respuestas){

        $validator = Validator::make($respuestas,[
            'respuestas.*.id_servicio_estudio' => 'required|exists:servicio_estados,id',
            'respuestas.*.id_catalogo_encuestas_pregunta' => 'required|exists:proyectos,id',
            'respuestas.*.respuesta' => 'required|string',
        ]);
        //FamiliasPadres

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }


        foreach ($respuestas as &$respuesta) {
            $respuesta['padre_monto'] = $respuesta['padre_monto'] ?? 0;
            $respuesta['madre_monto'] = $respuesta['madre_monto'] ?? 0;
            $respuesta['monto'] = $respuesta['monto'] ?? 0;
            $respuesta['valor'] = $respuesta['valor'] ?? 0;
        }

        $elementos = array();

        DB::beginTransaction();

        try{

            $deleted = ServiciosEstudiosRespuestas::where('id_servicio_estudio', $respuestas[0]['id_servicio_estudio'])->where('id_catalogo_encuestas_pregunta',$respuestas[0]['id_catalogo_encuestas_pregunta'])->delete();
            foreach ($respuestas as $respuesta) {
                $elemento[] = ServiciosEstudiosRespuestas::create($respuesta);
            }
            //$elemento = ServicioEstudio::create($validator->validate());

            // Si todo va bien, confirmamos la transacción
            DB::commit();

            return response()->json(['message' => 'Nuevo elemento creado', 'data' => $elemento], 201);
        } catch (\Exception $e) {
            // Si ocurre algún error, se deshacen todas las inserciones
            DB::rollBack();
            return response()->json(['message' => 'Error durante la inserción de datos', 'error' => $e->getMessage()], 500);
        }
    }

    private function respuestaTipoSeis($respuestas){

        $validator = Validator::make($respuestas,[
            'respuestas.*.id_servicio_estudio' => 'required|exists:servicio_estados,id',
            'respuestas.*.id_catalogo_encuestas_pregunta' => 'required|exists:proyectos,id',
        ]);
        //FamiliasPadres

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }


        foreach ($respuestas as &$respuesta) {
            $respuesta['padre_monto'] = $respuesta['padre_monto'] ?? 0;
            $respuesta['madre_monto'] = $respuesta['madre_monto'] ?? 0;
            $respuesta['monto'] = $respuesta['monto'] ?? 0;
            $respuesta['valor'] = $respuesta['valor'] ?? 0;
        }

        $elementos = array();

        DB::beginTransaction();

        try{

            $deleted = ServiciosEstudiosRespuestas::where('id_servicio_estudio', $respuestas[0]['id_servicio_estudio'])->where('id_catalogo_encuestas_pregunta',$respuestas[0]['id_catalogo_encuestas_pregunta'])->delete();
            foreach ($respuestas as &$respuesta) {
                $elemento[] = ServiciosEstudiosRespuestas::create($respuesta);
            }
            //$elemento = ServicioEstudio::create($validator->validate());

            // Si todo va bien, confirmamos la transacción
            DB::commit();

            return response()->json(['message' => 'Nuevo elemento creado', 'data' => $elemento], 201);
        } catch (\Exception $e) {
            // Si ocurre algún error, se deshacen todas las inserciones
            DB::rollBack();
            return response()->json(['message' => 'Error durante la inserción de datos', 'error' => $e->getMessage()], 500);
        }
    }

    private function respuestaTipoSiete($respuestas){

        $validator = Validator::make($respuestas,[
            'respuestas.*.id_servicio_estudio' => 'required|exists:servicio_estados,id',
            'respuestas.*.id_catalogo_encuestas_pregunta' => 'required|exists:proyectos,id',
            'respuestas.*.vive' => 'required|boolean',
            'respuestas.*.activo' => 'required|boolean',
            'respuestas.*.respuesta' => 'nullable|string',
        ]);
        //FamiliasPadres

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }


        foreach ($respuestas as &$respuesta) {
            $respuesta['padre_monto'] = $respuesta['padre_monto'] ?? 0;
            $respuesta['madre_monto'] = $respuesta['madre_monto'] ?? 0;
            $respuesta['monto'] = $respuesta['monto'] ?? 0;
            $respuesta['valor'] = $respuesta['valor'] ?? 0;
        }

        $elementos = array();

        DB::beginTransaction();

        try{

            $deleted = ServiciosEstudiosRespuestas::where('id_servicio_estudio', $respuestas[0]['id_servicio_estudio'])->where('id_catalogo_encuestas_pregunta',$respuestas[0]['id_catalogo_encuestas_pregunta'])->delete();
            foreach ($respuestas as &$respuesta) {
                $elemento[] = ServiciosEstudiosRespuestas::create($respuesta);
            }
            //$elemento = ServicioEstudio::create($validator->validate());

            // Si todo va bien, confirmamos la transacción
            DB::commit();

            return response()->json(['message' => 'Nuevo elemento creado', 'data' => $elemento], 201);
        } catch (\Exception $e) {
            // Si ocurre algún error, se deshacen todas las inserciones
            DB::rollBack();
            return response()->json(['message' => 'Error durante la inserción de datos', 'error' => $e->getMessage()], 500);
        }
    }

    private function respuestaTipoOcho($respuestas){

        $validator = Validator::make($respuestas,[
            'respuestas.*.id_servicio_estudio' => 'required|exists:servicio_estados,id',
            'respuestas.*.id_catalogo_encuestas_pregunta' => 'required|exists:proyectos,id',
            'respuestas.*.texto' => 'required|string',
        ]);
        //FamiliasPadres

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }


        foreach ($respuestas as &$respuesta) {
            $respuesta['padre_monto'] = $respuesta['padre_monto'] ?? 0;
            $respuesta['madre_monto'] = $respuesta['madre_monto'] ?? 0;
            $respuesta['monto'] = $respuesta['monto'] ?? 0;
            $respuesta['valor'] = $respuesta['valor'] ?? 0;
        }

        $elementos = array();

        DB::beginTransaction();

        try{

            $deleted = ServiciosEstudiosRespuestas::where('id_servicio_estudio', $respuestas[0]['id_servicio_estudio'])->where('id_catalogo_encuestas_pregunta',$respuestas[0]['id_catalogo_encuestas_pregunta'])->delete();
            foreach ($respuestas as &$respuesta) {
                $elemento[] = ServiciosEstudiosRespuestas::create($respuesta);
            }
            //$elemento = ServicioEstudio::create($validator->validate());

            // Si todo va bien, confirmamos la transacción
            DB::commit();

            return response()->json(['message' => 'Nuevo elemento creado', 'data' => $elemento], 201);
        } catch (\Exception $e) {
            // Si ocurre algún error, se deshacen todas las inserciones
            DB::rollBack();
            return response()->json(['message' => 'Error durante la inserción de datos', 'error' => $e->getMessage()], 500);
        }
    }
}
