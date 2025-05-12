<?php

namespace App\Http\Controllers;

use App\FamiliasPadres;
use App\OrdenesServicio;
use App\Proyectos;
use App\RegistroToken;
use App\ServicioEstudio;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class RegistroExternoController extends Controller
{
    public function registroExternoToken(Request $request, $token) {
        $tokenRegistro = RegistroToken::where('token_parte1', $token)->where('activo', 1)->first();
        //return response()->json(['data'=> $request->all(), 'token'=> $tokenRegistro]);
        $datos = $request->all();
        if (empty($tokenRegistro)) {
            return response()->json(['error'=> true, 'message'=> 'Token no valido.', 'registro' => false], 404); 
        }
        
        $existe = User::select('*')->where('email', $datos['email'])->first();
        $dataSetCaracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $mesclar = str_shuffle($dataSetCaracteres);
        $nuevaContraseña = substr($mesclar, 0, 8);
        
        if ($existe !== null) {
            //array_push($usuariosExistentes, ["error" => "Email previamente registrado", 'tipo' => 'existe', "familia" => $existe]);
            $existe->activo = true;
            $existe->active = true;

            

            $passReactive = $nuevaContraseña;
            $existe->password = bcrypt($passReactive);
            $existe->password_temporal = $passReactive;
            $existe->save();
            return response()->json(['error'=> false, 'message' => 'Usuario ' . $existe->email . ' reactivado y actualizado.', 'registro' => true]);
        } else {
            // enpieza el insert
            //1. crear USUARIO

            $newUser = [];
            $newUser['id_perfil'] = 6;
            $newUser['id_cliente'] = $datos['id_cliente'];
            $newUser['password_temporal'] = $nuevaContraseña;
            $newUser['externo'] = 1;

            $dataDireccion = [
                'numero_exterior' => $datos['numero_exterior'],
                'calle' => $datos['calle'],
                'colonia' => $datos['colonia'],
                'municipio' => $datos['municipio'],
                'estado' => $datos['estado'],
                'codigo_postal' => $datos['codigo_postal'],
                'pais' => $datos['pais'],
            ];


            $direccion = $this->crearDireccion($dataDireccion);

            $response = Http::withHeaders([
                'User-Agent' => 'SinergiaEstudiosMX/1.0', // Configuración del User-Agent
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $direccion,
                'format' => 'json',
                'addressdetails' => 1,
            ]);

            if ($response->successful()) {
                $dataResp = json_decode($response->body());
                if (empty($dataResp)) {
                    //return response()->json(['data' => 'No contiene datos']);
                    $lat = null;
                    $lon = null;
                } else {
                    //return response()->json(['datos' => $dataResp, 'estatus' => true]);
                    $direccion = $dataResp[0]->display_name; //$display_name;// = $dataResp[0]->display_name;
                    //return response()->json(['direccion' => $display_name, 'estatus' => true]);
                    $lat = $dataResp[0]->lat;
                    $lon = $dataResp[0]->lon;
                }
            }


            $newUser = [
                'name' => $datos['candidato'] === '' ? null : $datos['candidato'],
                'email' => $datos['padre']['contecto_principal'] == true ?  $datos['padre']['email'] : $datos['madre']['email'],
                'id_perfil' => 6,
                'id_cliente' => $datos['id_cliente'] ,
                'password' => bcrypt($nuevaContraseña),
                'password_temporal' => $nuevaContraseña,
                'latitud' => $lat ?? null,
                'longitud' => $lon ?? null,
                'direccion' => $direccion,
                'calle' => $datos['calle'] ?? null,
                'numero_exterior' => strval($datos['numero_exterior'] ) ?? '',
                'colonia' => $datos['colonia'] ?? null,
                'municipio' => $datos['municipio'] ?? null,
                'estado' => $datos['estado'] ?? null,
                'codigo_postal' => strval($datos['codigo_postal']) ?? null,
                'pais' => $datos['pais'] ?? null,
                'externo' => 1,
            ];

            $validator = Validator::make($newUser, [
                'name' => 'required|present|string|max:255',
                'email' => ['required', 'email:rfc,dns','regex:/^[^@]+@[^@]+\.[a-z]{2,}$/i', 'max:100', 'unique:users', 'present'],
                'id_perfil' => 'required|int',
                'id_cliente' => 'nullable|int',
                'latitud' => 'nullable|string',
                'longitud' => 'nullable|string',
                'direccion' => 'nullable|string',
                'calle' => 'nullable|string',
                'numero_exterior' => 'nullable|string',
                'colonia' => 'nullable|string',
                'municipio' => 'nullable|string',
                'estado' => 'nullable|string',
                'codigo_postal' => 'nullable|string',
                'pais' => 'nullable|string',
                'externo' => 'boolean',
            ]);

            if ($validator->fails()) {
                $errorsRow = $validator->errors();
                return response()->json(['error'=> true, 'message'=> 'Datos de cuenta no validos.', 'errores' => $errorsRow, 'registro' => false], 422); 
            }

            $user = User::create($newUser);
            $userID = $user->id;

            //2. crear Caso Servicio Estudio
            $directorio = $this->setDirectorioEstudio($userID);

            $newServicioEconomico = [
                'id_servicio_estado' => 1,
                'id_proyecto' => $datos['id_proyecto'],
                'id_cliente' => $datos['id_cliente'],
                'id_familia' => $userID,
                'id_orden_servicio' => $datos['id_orden_servicio'],
                'es_cliente_comun' => 0,
                'directorio' => $directorio,
                'candidato' => $datos['candidato'],
                'situacion' => 'EN PROCESO',
                'email' => $datos['email'],
                'direccion' => $direccion,
                'calle' => $datos['calle'],
                'numero_exterior' => $datos['numero_exterior'],
                'colonia' => $datos['colonia'],
                'municipio' => $datos['municipio'],
                'estado' => $datos['estado'],
                'codigo_postal' => $datos['codigo_postal'],
                'pais' => $datos['pais'],
                'clave_familia_colegio'=> $datos['clave_familia'] ? $datos['clave_familia'] : '0000000'
            ];

            $servNew = ServicioEstudio::create($newServicioEconomico);

            $newPadre = [
                'id_familias_padres_tipo' => 1,
                'nombre' => $datos['padre']['nombre'] ? $datos['padre']['nombre'] : '',
                'vive' =>  $datos['padre']['vive'] == true ? 1 : 0,
                'direccion' => $datos['padre']['direccion'] ? $datos['padre']['direccion'] : '',
                'email' => $datos['padre']['email'] ? $datos['padre']['email']  : '',
                'id_servicio_estudio' => $servNew->id,
                'contecto_principal' => $datos['padre']['contecto_principal'] == true ? 1 : 0,
                'edad' => $datos['padre']['edad'] ? $datos['padre']['edad'] : 0 
            ];

            $newMadre = [
                'id_familias_padres_tipo' => 2,
                'nombre' => $datos['madre']['nombre'] ? $datos['madre']['nombre'] : '',
                'vive' => $datos['madre']['vive'] == true ? 1 : 0,
                'direccion' => $direccion,
                'email' => $datos['madre']['email'] ? $datos['padre']['email']  : '',
                'id_servicio_estudio' => $servNew->id,
                'contecto_principal' => $datos['madre']['contecto_principal'] == true ? 1 : 0,
                'edad' => $datos['madre']['edad'] ? $datos['madre']['edad'] : 0 

            ];

            try {
                FamiliasPadres::create($newPadre);
                FamiliasPadres::create($newMadre);
            } catch (\Throwable $th) {
                //throw $th;
            }
            
            //$addPadreMadre = FamiliasPadres

            if ($lat !== null && $lon !== null) { // se asginan colaboradres
                //DB::rollBack();
                $colabs = User::where('id_perfil', 4)->where('active', 1)->get();
                $userFamiliaDistancia = [];
                foreach ($colabs as $colab) {
                    if ($colab->latitud && $colab->longitud) {
                        $distancia = $this->calcularDistanciaColabFamilia(floatval($colab->latitud), floatval($colab->longitud), floatval($lat), floatval($lon));
                        array_push($userFamiliaDistancia, ['distancia' => $distancia, 'calab' => $colab->id, 'se' => $servNew->id]);
                    }
                }

                $minDistancia = collect($userFamiliaDistancia)->sortBy('distancia')->first();
                if ($minDistancia) {
                    // Actualizar el registro en la base de datos
                    $servNew->update([
                        'id_colaborador' => $minDistancia['calab']
                    ]);
                }
                //return response()->json(['colabs' => $colabs, 'servcreado' => $servNew, 'comparacion' => $userFamiliaDistancia]);
            } else {
                array_push($familiasNoAsignadas, ['familia' => $servNew]);
            }

        }
        return response()->json(['error' => false, 'data'=> $request->all(), 'registro' => true]);
    }

    public function crearDireccion($data)
    { // FUNCION PARA GENERAR DIRECCION PARA BUSCAR EN API
        $numero_exterior = $data['numero_exterior'] ?? null;
        $calle = $data['calle'] ?? null;
        $colonia = $data['colonia'] ?? null;
        $municipio = $data['municipio'] ?? null;
        $estado = $data['estado'] ?? null;
        $codigo_postal = $data['codigo_postal'] ?? null;
        $pais = $data['pais'] ?? null;

        return ($numero_exterior ? $numero_exterior . "," : "") .
            ($calle ? $calle . "," : "") .
            ($colonia ? $colonia . "," : "") .
            ($municipio ? $municipio . "," : "") .
            ($estado ? $estado . "," : "") .
            ($codigo_postal ? $codigo_postal . "," : "") .
            ($pais ? $pais : "");
    }

    private function setDirectorioEstudio($id)
    {
        $directorio = '';

        $editar = ServicioEstudio::where('id', $id)->first();
        if (!$editar) {
            return '';
        }

        $proyecto = Proyectos::where('id', $editar->id_proyecto)->first();
        $directorio .= $this->limpiarCadena($proyecto->nombre);
        $ordenServicio = OrdenesServicio::where('id', $editar->id_orden_servicio)->first();
        $directorio .= "/" . $ordenServicio->id . '_' . $this->limpiarCadena($ordenServicio->descripcion);
        $directorio .= "/" . $id . '_' . $this->limpiarCadena($editar->candidato);


        $editar->directorio = $directorio . "/";
        $editar->save();

        return $directorio;
    }

    function limpiarCadena($cadena)
    {
        // Convertir los espacios en guiones bajos
        $cadena = str_replace(' ', '_', $cadena);

        // Eliminar todos los caracteres que no sean letras o números (quitar caracteres especiales)
        $cadena = preg_replace('/[^a-zA-Z0-9_]/', '', $cadena);

        return $cadena;
    }

    public function calcularDistanciaColabFamilia($lat1, $lon1, $lat2, $lon2)
    {
        //$lat_familia, $let_familia
        $radioTierra = 6371; // Radio de la Tierra en kilómetros o millas

        // Convertir grados a radianes
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Diferencias de latitud y longitud
        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        // Fórmula del haversine
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Distancia
        return $radioTierra * $c;
    }

}
