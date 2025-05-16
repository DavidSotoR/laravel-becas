<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\CatalogoEncuestas;

class CatalogoEncuestasController extends Controller
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
        $query = CatalogoEncuestas::query();
        $query->with('tipoCliente');

        //Filtrar encuestas por filtro de cliente empresa o escuelas
        if($request->id_tipo_cliente){
            $query->where('id_tipo_cliente', $request->id_tipo_cliente);
        }

        $lista = $query->get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = CatalogoEncuestas::with('tipoCliente','preguntas')->where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'id_tipo_cliente' => 'required|int',
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        $elemento = CatalogoEncuestas::create(
            $validator->validate()
        );

        return response()->json(['message' => 'Nuevo cliente creado', 'data' => $elemento], 201);
    }

    public function editar(Request $request){
        $id = $request->id;

        $validator = Validator::make($request->all(),[
            'nombre' => ['required','string','min:2',Rule::unique('catalogo_encuestas')->ignore($id)],
            'descripcion' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = CatalogoEncuestas::where('id',$id)->first();
        $editar->nombre = $request->nombre;
        $editar->descripcion = $request->descripcion;
        $editar->save();


        return response()->json(['message' => 'Encuesta modificada', 'data' => $editar], 201);
    }


    public function copia(Request $request, $id){

        $elemento = CatalogoEncuestas::with('preguntas', 'parametros.items')->find($id);

        if (!$elemento) {
            return response()->json(['error' => 'Encuesta no encontrada'], 404);
        }

        if (isset($request->nombre)) {
            $request->merge([
                'nombre' => $elemento->nombre . " - " . $request->nombre
            ]);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'min:2', Rule::unique('catalogo_encuestas')->ignore($id)],
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verificar si la contraseña es correcta
        $user = auth()->user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'errors' => [
                    'password' => ['La contraseña no es correcta.'],
                ]
            ], 302);
        }

        \DB::beginTransaction();

        try {

            $nuevoElemento = $elemento->replicate();
            $nuevoElemento->nombre = $request->nombre;
            $nuevoElemento->save();

            $idsParametrosMap = [];
            $idsPreguntasMap = [];

            // Clonar parámetros con sus items
            foreach ($elemento->parametros as $parametro) {
                $nuevoParametro = $parametro->replicate();
                $nuevoElemento->parametros()->save($nuevoParametro);

                foreach ($parametro->items as $item) {
                    $nuevoItem = $item->replicate();
                    $nuevoItem->id_catalogo_encuestas_preguntas_parametro_clasificacion = $nuevoParametro->id;

                    // Se actualizará id_catalogo_encuestas_preguntas después de clonar preguntas
                    $nuevoItem->save();

                    // Guardamos la relación original de item para poder actualizarlo luego
                    $itemsMap[] = [
                        'nuevoItem' => $nuevoItem,
                        'originalItem' => $item
                    ];
                }

                $idsParametrosMap[$parametro->id] = $nuevoParametro->id;
            }

            // Clonar preguntas con referencias actualizadas
            foreach ($elemento->preguntas as $pregunta) {
                $nuevaPregunta = $pregunta->replicate();

                if ($pregunta->id_catalogo_encuestas_preguntas_parametro_clasificacion !== null) {
                    $nuevaPregunta->id_catalogo_encuestas_preguntas_parametro_clasificacion = $idsParametrosMap[$pregunta->id_catalogo_encuestas_preguntas_parametro_clasificacion] ?? null;
                }

                if ($pregunta->id_parametro_clasificacion_parametro_adicional_uno !== null) {
                    $nuevaPregunta->id_parametro_clasificacion_parametro_adicional_uno = $idsParametrosMap[$pregunta->id_parametro_clasificacion_parametro_adicional_uno] ?? null;
                }

                if ($pregunta->id_parametro_clasificacion_parametro_adicional_dos !== null) {
                    $nuevaPregunta->id_parametro_clasificacion_parametro_adicional_dos = $idsParametrosMap[$pregunta->id_parametro_clasificacion_parametro_adicional_dos] ?? null;
                }

                $nuevoElemento->preguntas()->save($nuevaPregunta);
                $idsPreguntasMap[$pregunta->id] = $nuevaPregunta->id;
            }

            // Actualizar id_catalogo_encuestas_preguntas en los items nuevos
            if (!empty($itemsMap)) {
                foreach ($itemsMap as $map) {
                    $nuevoItem = $map['nuevoItem'];
                    $originalItem = $map['originalItem'];

                    if ($originalItem->id_catalogo_encuestas_preguntas && isset($idsPreguntasMap[$originalItem->id_catalogo_encuestas_preguntas])) {
                        $nuevoItem->id_catalogo_encuestas_preguntas = $idsPreguntasMap[$originalItem->id_catalogo_encuestas_preguntas];
                        $nuevoItem->save();
                    }
                }
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'nueva_encuesta_id' => $nuevoElemento->id,
                'mensaje' => 'Encuesta copiada correctamente.'
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Error al copiar la encuesta', 'mensaje' => $e->getMessage()], 500);
        }
    }
}
