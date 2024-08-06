<?php

namespace App\Http\Controllers;

use App\Proyectos;
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
}
