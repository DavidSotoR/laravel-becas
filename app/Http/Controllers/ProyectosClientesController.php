<?php

namespace App\Http\Controllers;

use App\ProyectosClientes;
use App\Clientes;
use App\Proyectos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProyectosClientesController extends Controller
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

    public function lista(Request $request,$id_proyecto){
        //Lista de clientes
        $lista = array();

        $proyectos = Proyectos::findOrFail($id_proyecto);

        if ($request->has('no_enlazados') && $request->no_enlazados) {

            $queryClientes = Clientes::query();

            $queryClientes->whereDoesntHave('proyectos', function($query) use ($id_proyecto) {
                $query->where('id_proyecto', $id_proyecto);
            });

            if(isset($request->id_tipo_cliente)){
                $queryClientes->where('id_tipo_cliente', $request->id_tipo_cliente);
            }

            $queryClientes->whereNotNull('id_catalogo_encuesta');

            $lista = $queryClientes->get();

        } else {
            $lista = $proyectos->clientes()->get();
        }

        return response()->json($lista);
    }

    public function clientesEncuestaLista(Request $request,$id_proyecto){

        $lista = ProyectosClientes::with(['cliente', 'encuesta'])
            ->where('id_proyecto', $id_proyecto)
            ->get();

        return response()->json($lista);
    }

    public function id($id){
        $elemento = ProyectosClientes::where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request,$id_proyecto){

        $validator = Validator::make(['id_proyecto' => $id_proyecto], [
            'id_proyecto' => 'required|integer|exists:proyectos,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validator = Validator::make($request->all(),[
            'lista_clientes' => 'required|array',
            'lista_clientes.*' => 'integer|exists:clientes,id',
        ]);

        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        $errores = [];
        foreach ($request->input('lista_clientes') as $id_cliente) {

            $existe = ProyectosClientes::where('id_proyecto', $id_proyecto)
                                        ->where('id_cliente', $id_cliente)
                                        ->exists();

            $cliente = Clientes::where('id', $id_cliente)
                                ->whereNotNull('id_catalogo_encuesta')
                                ->first();

           // return response()->json(['message' => 'cliente.', 'errors' => $cliente], 422);
            if($existe){
                $errores[] = "El cliente con ID $id_cliente ya está asociado al proyecto.";
            }else if($cliente == null){
                $errores[] = "El cliente con ID $id_cliente no cuenta con una encuesta asignada.";
            }else{
                ProyectosClientes::create([
                    'id_proyecto' => $id_proyecto,
                    'id_cliente' => $id_cliente,
                    'id_encuesta' => $cliente->id_catalogo_encuesta,
                ]);
            }
        }

        if (!empty($errores)) {
            return response()->json(['message' => 'Algunos clientes no fueron insertados.', 'errors' => $errores], 422);
        }

        return response()->json(['message' => 'Nuevo elemento creado', 'data' => $cliente], 201);
    }

    public function eliminar($id = 0){
        if(!$id){
            return response()->json(["id"=> ["El campo id es requerido."]], 400);
        }

        $eliminar = ProyectosClientes::where('id',$id)->delete();

        return response()->json(['message' => 'Elemento modificado', 'data' => $eliminar], 201);
    }
}
