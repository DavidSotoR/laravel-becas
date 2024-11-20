<?php

namespace App\Http\Controllers;

use App\Proyectos;
use App\Clientes;
use App\OrdenesServicio;
use App\ProyectosClientes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProyectosController extends Controller
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
        $query = Proyectos::query()->with('tipoCliente');

        if(isset($request->id_tipo_cliente)){
            $query->where('id_tipo_cliente', $request->id_tipo_cliente);
        }

        if(isset($request->activo)){
            $query->where('activo', $request->activo);
        }


        $lista = $query->get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = Proyectos::with(['tipoCliente','clientes'])->where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'activo' => 'boolean',
            'nombre' => 'required|string|unique:proyectos',
            'id_tipo_cliente' => 'required|int',
        ]);


        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        $data = $validator->validated();
        $data['activo'] = $data['activo'] ?? 1;

        $cliente = Proyectos::create($data);

        return response()->json(['message' => 'Nuevo elemento creado', 'data' => $cliente], 201);
    }

    public function editar(Request $request){
        $id = $request->id;
        $validator = Validator::make($request->all(),[
            'id' => 'required',
            'activo' => 'required',
            'nombre' => ['required','string', Rule::unique('proyectos')->ignore($id)],
            'id_tipo_cliente' => 'required|int',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = Proyectos::where('id',$id)->first();
        $editar->activo = $request->activo;
        $editar->nombre = $request->nombre;
        $editar->id_tipo_cliente = $request->id_tipo_cliente;
        $editar->save();

        return response()->json(['message' => 'Elemento modificado', 'data' => $editar], 201);
    }
    public function listaProyectosXPerfil(Request $request){

        $user = auth()->user();
        $id_cliente = $user->id_cliente;
        $perfil_nombre = $user->perfil->nombre;

        if($perfil_nombre !== "Empresas"){
            return response()->json([]);
        }

        $cliente = Clientes::find($id_cliente);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $lista = $cliente->proyectos()
            //->where('activo', 1)
            ->where('id_tipo_cliente', 1)
            ->get();

            foreach($lista as &$proyecto){
                $ordenesDeServicio = OrdenesServicio::where('id_proyecto',$proyecto->id)->where('id_cliente',$id_cliente)->get();
                $proyecto['ordenes_de_servicio'] = $ordenesDeServicio;
            }



        return response()->json($lista);
    }

    public function proyectoIDEmpresa($id_proyecto){
        $user = auth()->user();
        $id_cliente = $user->id_cliente;
        $perfil_nombre = $user->perfil->nombre;

        if($perfil_nombre !== "Empresas"){
            return response()->json([]);
        }

        $cliente = Clientes::find($id_cliente);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $elemento = $cliente->proyectos()
            ->where('proyectos.id', $id_proyecto)
            //->where('activo', 1)
            ->where('id_tipo_cliente', 1)
            ->first();

        $proyectoCliente = ProyectosClientes::
                                        where('id_proyecto', $id_proyecto)
                                        ->where('id_cliente', $id_cliente)
                                        ->first();

        $elemento['id_encuesta'] = $proyectoCliente->id_encuesta;

        return response()->json($elemento);
    }
}
