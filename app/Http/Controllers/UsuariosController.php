<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\User;

class UsuariosController extends Controller
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
        $query = User::query()->with('perfil','cliente');

        if(isset($request->id_cliente)){
            if($request->id_cliente == 0){
                $query->where('id_cliente',null);
            }else{
                $query->where('id_cliente',$request->id_cliente);
            }
        }

        if(isset($request->tipos)){
            if($request->tipos == 'internos'){
                $query->whereHas('perfil', function ($query) {
                    $query->where('interno', true);
                });
            }
            if($request->tipos == 'externos'){
                $query->whereHas('perfil', function ($query) {
                    $query->where('interno', false);
                });
            }
        }

        $lista = $query->get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = User::with('perfil','cliente')->where('id',$id)->first();
        return response()->json($elemento);
    }

    public function editar(Request $request){
        $id = $request->id;
        $validator = Validator::make($request->all(),[
            'id' => 'required|int',
            'name' => ['required','min:2', Rule::unique('users')->ignore($id)],
            'email' => 'required',
            'id_perfil' => 'required|int',
            'id_cliente' => 'nullable|int',
            'latiud' => 'nullable|string',
            'longitud' => 'nullable|string',
            'direccion' => 'nullable|string',
            'externo' => 'boolean',
            'calle' => 'nullable|string',
            'numero_exterior' => 'nullable|string',
            'colonia' => 'nullable|string',
            'municipio' => 'nullable|string',
            'estado' => 'nullable|string',
            'codigo_postal' => 'nullable|string',
            'pais' => 'nullable|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = User::where('id',$id)->first();
        $editar->name = $request->name;
        $editar->email = $request->email;
        $editar->id_perfil = $request->id_perfil;
        $editar->id_cliente = $request->id_cliente;
        $editar->latitud = $request->latitud;
        $editar->longitud = $request->longitud;
        $editar->externo = $request->externo;
        $editar->direccion = $request->direccion;
        $editar->calle = $request->calle;
        $editar->numero_exterior = $request->numero_exterior;
        $editar->colonia = $request->colonia;
        $editar->municipio = $request->municipio;
        $editar->estado = $request->estado;
        $editar->codigo_postal = $request->codigo_postal;
        $editar->pais = $request->pais;
        $editar->save();


        return response()->json(['message' => 'Usuario modificado', 'data' => $editar], 201);
    }

    public function editarPassword($id,Request $request){

        $validator = Validator::make($request->all(),[
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'El campo password es requerido o no cumple con las validaciones.',
                'confirmado' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        if ($request->password === $request->password_confirmar) {
            $id = $request->id;
            $editar = User::where('id',$id)->first();
            $editar->password = bcrypt($request->password);
            $editar->password_temporal = null;
            //return response()->json($editar);
            $editar->save();

            return response()->json(['message' => 'Password modificado, vuelva a iniciar sesión.', 'confirmado' => true], 201);
        } else {
            return response()->json(['message' => 'Contraseñas no coinciden.', 'confirmado' => false], 400);
        }
        
    }

    public function disableOrEnable(Request $request){
        $id = $request->id;
        $editar = User::where('id',$id)->first();
        $editar->active = !$editar->active;
        $editar->save();

        return response()->json(['message' => 'Usuario modificado', 'data' => $editar], 201);
    }

    public function colaboradores(Request $request){
        $query = User::query()->with('perfil');

        $query->where('active',1);
        $query->whereHas('perfil', function($queryPerfilInterno) {
            $queryPerfilInterno->where('interno', '=', 1); //
        });

        $query->where(function($queryOR) {
            $queryOR->whereHas('perfil', function($query) {
                $query->where('id', '=', 4);
            })
                ->orWhere('asignar_estudios', '=', 1);
        });

        $lista = $query->get();
        return response()->json($lista);
    }

    public function calidad(Request $request){
        $query = User::query()->with('perfil');

        $query->where('active',1);
        $query->whereHas('perfil', function($queryPerfilInterno) {
            $queryPerfilInterno->where('interno', '=', 1);
            $queryPerfilInterno->where('id', '=', 3);
        });

        $lista = $query->get();
        return response()->json($lista);
    }
}
