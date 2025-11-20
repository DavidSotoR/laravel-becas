<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Familias;
use App\ServicioEstudio;
use App\FamiliasPadres;
use App\Mail\NotificacionCorreo;
use App\User;
use Illuminate\Support\Facades\Mail;

class FamiliasController extends Controller
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

    public function estudioSocioeconomicoEnviarCorreo($id, Request $request){
        $usuarioF = User::find($id);
        $data = $request->all();
        $usuarioF->candidato = $data['candidato'];
        try {
            if ($usuarioF) {
                $resp = Mail::to([$usuarioF['email']])->send(new NotificacionCorreo($usuarioF));
                return response()->json(['message'=> 'se envio correctamente el correo.', 'user' => $usuarioF]);
            } else{
                return response()->json(['message'=> 'Correo no enviado.', "user" => $usuarioF]);
            }
        } catch (\Throwable $th) {
            return response()->json(['message'=> 'Correo no enviado.', 'error' => $th->getMessage()]);
        }
        

        
    }


    public function lista(Request $request){
        $query = Familias::query();

        //filtrat por siclo escolar id_ciclo_escolar
        /*if(isset($request->id_ciclo_escolar)){
            $query->where('id_ciclo_escolar', $request->id_ciclo_escolar);
        }*/

        $lista = $query->get();
        return response()->json($lista);
    }

    public function estudioSocioeconomico($id_familia = 0){
        //$id_familia = Auth::user()->id;
        //return $id_familia;
        $elemento = ServicioEstudio::with(['cliente','proyecto'])->where('id_familia',$id_familia)->first();
        return response()->json($elemento);
    }

    public function estudioSocioeconomicoPadres($id_familia = 0){
        //$id_familia = Auth::user()->id;
        //return $id_familia;
        $elemento = ServicioEstudio::where('id_familia',$id_familia)->first();
        $padres  = FamiliasPadres::where('id_servicio_estudio', $elemento->id)->get();
        return response()->json($padres);
    }

    public function estudioSocioeconomicoPadresUpdate(Request $request){
        $idESE = $request->input('idESE', 0);  // Usando 0 como valor por defecto
        $padre = $request->input('padre');
        $madre = $request->input('madre');
        $direccion = $request->input('direccion');
        $lon = $request->input('lon');
        $lat = $request->input('lan');

        if ($idESE === 0) {
            $padreCreado = FamiliasPadres::create([
                'nombre' => $padre['nombre'],
                'edad' => $padre['edad'],
                'vive' => $padre['vive'],
                'direccion' => $padre['direccion'] ?? '',
                'ocupacion_actual' => $padre['ocupacion_actual'],
                'empresa_trabajo' => $padre['empresa_trabajo'],
                'email' => $padre['email'],
                'telefono_casa' => $padre['telefono_casa'],
                'contecto_principal' => $padre['contecto_principal'],
            ]);
    
            $madreCreada = FamiliasPadres::create([
                'nombre' => $madre['nombre'],
                'edad' => $madre['edad'],
                'vive' => $madre['vive'],
                'direccion' => $madre['direccion'] ?? '',
                'ocupacion_actual' => $madre['ocupacion_actual'],
                'empresa_trabajo' => $madre['empresa_trabajo'],
                'email' => $madre['email'],
                'telefono_casa' => $madre['telefono_casa'],
                'contecto_principal' => $madre['contecto_principal'],
            ]);
        } else {
            $padreESE = FamiliasPadres::find($padre['id']);
            if ($padreESE) {
                $padreESE->update([
                    'nombre' => $padre['nombre'],
                    'edad' => $padre['edad'],
                    'vive' => $padre['vive'],
                    'direccion' => $padre['direccion'] ?? 'PENDIENTE',
                    'ocupacion_actual' => $padre['ocupacion_actual'],
                    'empresa_trabajo' => $padre['empresa_trabajo'],
                    'email' => $padre['email'],
                    'telefono_casa' => $padre['telefono_casa'],
                    'contecto_principal' => $padre['contecto_principal'],
                ]);
            }

            $madreESE = FamiliasPadres::find($madre['id']);
            if ($madreESE) {
                $madreESE->update([
                    'nombre' => $madre['nombre'],
                    'edad' => $madre['edad'],
                    'vive' => $madre['vive'],
                    'direccion' => $madre['direccion'] ?? 'PENDIENTE',
                    'ocupacion_actual' => $madre['ocupacion_actual'],
                    'empresa_trabajo' => $madre['empresa_trabajo'],
                    'email' => $madre['email'],
                    'telefono_casa' => $madre['telefono_casa'],
                    'contecto_principal' => $madre['contecto_principal'],
                ]);
            }
        }
        $servicioEstudio = ServicioEstudio::find($idESE); // Usamos el idESE para buscar el registro del servicio
        $dataPost = [
            "a" => $idESE,
            "b" => $padre,
            "c" => $direccion,
            "d" => $lon,
            "e" => $lat,
            "f" => $madre,
        ];
        if ($servicioEstudio) {
            $servicioEstudio->update([
                'direccion' => $direccion ?? 'PENDIENTE',
                'latitud' => $lat ?? null,
                'longitud' => $lon ?? null,
            ]);

            return response()->json(['message' => 'Registro se ha guardado correctamente', 'data' => $dataPost]);
        } else {
            return response()->json(['message' => 'No se encontro registro del Estudio Socioeconomico.', 'data' => $dataPost]);
        }
        
        
    }

    public function id($id){
        $elemento = Familias::where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(),[
            'nombre' => 'required',
            //'id_ciclo_escolar' => 'required|int',
            'situacion_beca' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $cliente = Familias::create($validator->validate());

        return response()->json(['message' => 'Nuevo registro creado', 'data' => $cliente], 201);
    }
}
