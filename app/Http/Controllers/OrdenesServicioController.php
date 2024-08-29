<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\OrdenesServicio;

class OrdenesServicioController extends Controller
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

    public function lista(Request $request,$id_proyecto,$id_cliente){
        $query = OrdenesServicio::query()
                    ->where('id_proyecto',$id_proyecto)
                    ->where('id_cliente',$id_cliente);

        if(isset($request->activo)){
            $query->where('activo', $request->activo);
        }

        $lista = $query->get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = OrdenesServicio::where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'id_proyecto' => 'required|exists:proyectos,id',
            'id_cliente' => 'required|exists:clientes,id',
            'descripcion' => 'required|string|max:255',
            'notas' => 'nullable|string',
            'fecha_estimada_entrega' => 'required|date',
            'fecha_real_entrega' => 'nullable|date',
            'fecha_estimada_finalizacion' => 'required|date|after_or_equal:fecha_estimada_entrega',
            'fecha_real_finalizacion' => 'nullable|date|after_or_equal:fecha_real_entrega',
            'activo' => 'required|boolean',
        ]);


        if($validator->fails()){
            return response()->json(["errors"=>$validator->errors()], 400);
        }

        //$data = $validator->validated();
        //$data['activo'] = $data['activo'] ?? 1;

        $cliente = OrdenesServicio::create($validator->validate());

        return response()->json(['message' => 'Nuevo elemento creado', 'data' => $cliente], 201);
    }

    public function editar(Request $request,$id){

        $orden = OrdenesServicio::findOrFail($id);

        $validator = Validator::make($request->all(),[
            'id_proyecto' => 'required|exists:proyectos,id',
            'descripcion' => 'required|string|max:255',
            'notas' => 'nullable|string',
            'fecha_estimada_entrega' => 'required|date',
            'fecha_real_entrega' => 'nullable|date',
            'fecha_estimada_finalizacion' => 'required|date|after_or_equal:fecha_estimada_entrega',
            'fecha_real_finalizacion' => 'nullable|date|after_or_equal:fecha_real_entrega',
            'activo' => 'required|boolean',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = $orden->update($validator->validate());

        return response()->json(['message' => 'Elemento modificado', 'data' => $editar], 201);
    }
}
