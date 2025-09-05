<?php

namespace App\Http\Controllers;

use App\Clientes;
use App\Sucursales;
use Illuminate\Http\Request;

class ModuloEmpresasController extends Controller
{
    public function getEmpresasCliente(Request $request){
        $listaEmpresas = Clientes::where('id_tipo_cliente', 2)->get();

        return response()->json($listaEmpresas);
    }

    public function getSucursales(Request $request){
        $idCliente = $request->input('id_cliente');

        if (!empty($idCliente) && $idCliente != 0) {
            $lista = Sucursales::where('id_cliente', $idCliente)->get();
        } else {
            $lista = Sucursales::all();
        }

        return response()->json($lista);
    }

    public function postNuevaSucursal(Request $request){
        $data = $request->all();
        $exist = Sucursales::where('id_cliente', $data['id_cliente'])->where('nombre',$data['nombre'])->first();
        if ($exist) {
            return response()->json([ "error" => true, "message" => "Ya existe una sucursal con el nombre: ".$data['nombre'] ]);
        }

        try {
            $nueva = Sucursales::create($data);
            return response()->json([ "error" => false, "message" => "SE CREO LA SUCURSAL CORRECTAMENTE", "data" => $nueva ]);
        } catch (\Throwable $th) {
            return response()->json([ "error" => true, "message" => "OCURRIO UN ERROR EN EL SERVIDOR", "data" => $th ]);
        }
        
    }
    
}
