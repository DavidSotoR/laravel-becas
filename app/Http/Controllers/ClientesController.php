<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Clientes;

class ClientesController extends Controller
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

        $query = Clientes::query()->with("tipoCliente");


        if(isset($request->id_tipo_cliente)){
            $query->where('id_tipo_cliente', $request->id_tipo_cliente);
        }

        if(isset($request->id_clientes_hermanos)){
            if($request->id_clientes_hermanos == 0){
                $query->where(function ($query) use ($request){
                    $query
                        ->where('id_clientes_hermanos', $request->id_clientes_hermanos)
                        ->orWhereNull('id_clientes_hermanos');
                });
            }else{
                $query->where('id_clientes_hermanos', $request->id_clientes_hermanos);
            }
        }

        $lista = $query ->get();

        return response()->json($lista);
    }

    public function id($id){
        $elemento = Clientes::with("tipoCliente")->where('id',$id)->first();
        return response()->json($elemento);
    }

    public function usuarios($id){
        $elemento = Clientes::with("tipoCliente","usuarios")->where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'nombre' => 'required|unique:clientes',
            'descripcion' => 'required',
            'notificaciones_email' => 'required',
            'id_tipo_cliente' => 'required',
            'id_clientes_hermanos' => 'nullable|int',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $cliente = Clientes::create($validator->validate());

        return response()->json(['message' => 'Nuevo cliente creado', 'data' => $cliente], 201);
    }

    public function editar(Request $request){
        $id = $request->id;
        $validator = Validator::make($request->all(),[
            'id' => 'required',
            'nombre' => ['required','min:2', Rule::unique('clientes')->ignore($id)],
            'descripcion' => 'required',
            'notificaciones_email' => 'required',
            'id_tipo_cliente' => 'required',
            'id_clientes_hermanos' => 'nullable|int',
            'id_catalogo_encuesta' => 'nullable|int',
            'documentacion_digital' => 'nullable|boolean',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = Clientes::where('id',$id)->first();
        $editar->nombre = $request->nombre;
        $editar->descripcion = $request->descripcion;
        $editar->notificaciones_email = $request->notificaciones_email;
        $editar->id_tipo_cliente = $request->id_tipo_cliente;
        if(isset($request->id_clientes_hermanos)){
            $editar->id_clientes_hermanos = $request->id_clientes_hermanos;
        }


        if(isset($request->id_catalogo_encuesta))
            $editar->id_catalogo_encuesta = $request->id_catalogo_encuesta;
            if(isset($request->documentacion_digital))
                $editar->documentacion_digital = $request->documentacion_digital;
        if(isset($request->rso))
            $editar->rso = $request->rso;
        if(isset($request->nombre_uno))
            $editar->nombre_uno = $request->nombre_uno;
        if(isset($request->telefono_uno))
            $editar->telefono_uno = $request->telefono_uno;
        if(isset($request->nombre_dos))
            $editar->nombre_dos = $request->nombre_dos;
        if(isset($request->telefono_dos))
            $editar->telefono_dos = $request->telefono_dos;
        if(isset($request->telefono_mobil))
            $editar->telefono_mobil = $request->telefono_mobil;
        if(isset($request->calle))
            $editar->calle = $request->calle;
        if(isset($request->entre_cale))
            $editar->entre_cale = $request->entre_cale;
        if(isset($request->colonia))
            $editar->colonia = $request->colonia;
        if(isset($request->codigo_postal))
            $editar->codigo_postal = $request->codigo_postal;
        if(isset($request->ciudad))
            $editar->ciudad = $request->ciudad;
        if(isset($request->estado))
            $editar->estado = $request->estado;
        if(isset($request->pais))
            $editar->pais = $request->pais;
        if(isset($request->rason_social))
            $editar->rason_social = $request->rason_social;
        if(isset($request->id_catalogo_encuesta))
            $editar->id_catalogo_encuesta = $request->id_catalogo_encuesta;
        if(isset($request->documentacion_digital))
            $editar->documentacion_digital = $request->documentacion_digital;


        $editar->save();


        return response()->json(['message' => 'Cliente modificado', 'data' => $editar], 201);
    }
}
