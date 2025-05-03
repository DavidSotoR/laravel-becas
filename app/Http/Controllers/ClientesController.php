<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Clientes;
use App\OrdenesServicio;
use App\Proyectos;
use App\ProyectosClientes;
use App\RegistroToken;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

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

    public function lista(Request $request)
    {

        $query = Clientes::query()->with("tipoCliente", "encuesta_asignada");


        if (isset($request->id_tipo_cliente)) {
            $query->where('id_tipo_cliente', $request->id_tipo_cliente);
        }

        if (isset($request->id_clientes_hermanos)) {
            if ($request->id_clientes_hermanos == 0) {
                $query->where(function ($query) use ($request) {
                    $query
                        ->where('id_clientes_hermanos', $request->id_clientes_hermanos)
                        ->orWhereNull('id_clientes_hermanos');
                });
            } else {
                $query->where('id_clientes_hermanos', $request->id_clientes_hermanos);
            }
        }

        $lista = $query->get();

        return response()->json($lista);
    }

    public function listaFiltrosClientesPorRoyecto($id)
    {
        $idProyecto = $id;

        $clientesProyecto = ProyectosClientes::select('id_cliente')->where('id_proyecto', $idProyecto)->get();
        if ($id == 0) {
            $query = Clientes::query()->with("tipoCliente", "encuesta_asignada");
        } else {
            $query = Clientes::query()->with("tipoCliente", "encuesta_asignada")->whereIn('id', $clientesProyecto);
        }
        //$query = Clientes::query()->with("tipoCliente", "encuesta_asignada")->whereIn('id', $clientesProyecto);

        $lista = $query->get();

        return response()->json($lista);
    }

    public function id($id)
    {
        /* $anioActual = Carbon::now()->year;
        $proyecto = Proyectos::with(['clientes'])->where('borrado', 0)->where('activo', 1)->where('anio_proyecto', $anioActual)->first();
        $proyectoClientes = $proyecto->clientes;
        $arrayClientes = [];
        foreach($proyectoClientes as $pc){
            array_push($arrayClientes, $pc['id']);
        }

        if (in_array($id, $arrayClientes)) {
            
            $ordenServicio = OrdenesServicio::where('id_cliente', $id)->where('id_proyecto', $proyecto->id)->where('activo', 1)->first();
            if (!empty($ordenServicio)) {
                $tokenLink = RegistroToken::where('id_cliente', $id)->where('id_proyecto', $proyecto->id)->where('id_orden_servicio', $ordenServicio->id)->first();
                
            }
            //dd($ordenServicio);
        } */
        $elemento = Clientes::with("tipoCliente")->where('id', $id)->first();

        return response()->json($elemento);
    }

    public function getDataLinkRegistro($id){
        $anioActual = Carbon::now()->year;
        $proyecto = Proyectos::with(['clientes'])->where('borrado', 0)->where('activo', 1)->where('anio_proyecto', $anioActual)->first();
        $proyectoClientes = $proyecto->clientes;
        $arrayClientes = [];
        foreach($proyectoClientes as $pc){
            array_push($arrayClientes, $pc['id']);
        }

        if (in_array($id, $arrayClientes)) {
            
            $ordenServicio = OrdenesServicio::where('id_cliente', $id)->where('id_proyecto', $proyecto->id)->where('activo', 1)->first();
            if (!empty($ordenServicio)) {
                $tokenLink = RegistroToken::where('id_cliente', $id)->where('id_proyecto', $proyecto->id)->where('id_orden_servicio', $ordenServicio->id)->first();
                
            }
            //dd($ordenServicio);
        }
        $elemento = Clientes::with("tipoCliente")->where('id', $id)->first();
        /* $arrEl = (array)$elemento;
        $arrLink = (array)$tokenLink;
        array_push($arrEl, $arrLink); */
        return response()->json(['cliente'=> $elemento, 'tokenLink' => $tokenLink]);
    }

    public function ordenesServicio(Request $request, $id_cleinte = 0)
    {
        if (!$id_cleinte) {
            return response()->json([]);
        }

        $query = OrdenesServicio::query();

        $query->where('id_cliente', $request->id_cliente);

        if (isset($request->id_tipo_cliente)) {
            $query->where('id_tipo_cliente', $request->id_tipo_cliente);
        }

        $lista = $query->get();

        return response()->json($lista);
    }

    public function usuarios($id)
    {
        $elemento = Clientes::with("tipoCliente", "usuarios")->where('id', $id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:clientes',
            'descripcion' => 'required',
            'notificaciones_email' => 'required',
            'id_tipo_cliente' => 'required',
            'id_clientes_hermanos' => 'nullable|int',
            'requiere_facturar' => 'boolean',
            'rfc' => 'nullable|string',
            'rso' => 'nullable|string',
            'nombre_uno' => 'nullable|string',
            'telefono_uno' => 'nullable|string',
            'nombre_dos' => 'nullable|string',
            'telefono_dos' => 'nullable|string',
            'telefono_mobil' => 'nullable|string',
            'calle' => 'nullable|string',
            'tipo_persona' => 'string',
            'entre_cale' => 'nullable|string',
            'colonia' => 'nullable|string',
            'codigo_postal' => 'nullable|string',
            'ciudad' => 'nullable|string',
            'estado' => 'nullable|string',
            'pais' => 'nullable|string',
            'rason_social' => 'nullable|string',
            'id_catalogo_encuesta' => 'nullable|int',
            'documentacion_digital' => 'nullable|boolean',
            'terminos' => 'nullable|string',
            'habilitar_resumen' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Validación del logo
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Crear cliente sin el logo
        $cliente = Clientes::create($validator->validate());

        // Verificar si se envió un logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logoNombre = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $rutaLogo = "clientes/{$cliente->id}/logo/"; // Carpeta destino
            $file->storeAs($rutaLogo, $logoNombre, 'public'); // Guardar en storage/app/public/

            // Actualizar cliente con la ruta del logo
            $cliente->update(['ubicacion_logo' => $rutaLogo . $logoNombre]);
            $cliente->save();
        }

        return response()->json([
            'message' => 'Nuevo cliente creado',
            'data' => $cliente
        ], 201);
    }

    public function editar(Request $request)
    {
        $id = $request->input('id'); // Obtener ID desde FormData
        //return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:clientes,id',
            'nombre' => ['required', 'min:2', Rule::unique('clientes')->ignore($id)],
            'descripcion' => 'required',
            'notificaciones_email' => 'required',
            'id_tipo_cliente' => 'required',
            'id_clientes_hermanos' => 'nullable|int',
            'id_catalogo_encuesta' => 'nullable|int',
            'documentacion_digital' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Manejo de archivos
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $cliente = Clientes::findOrFail($id);

        // Actualizar datos del cliente
        $cliente->update([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'notificaciones_email' => $request->input('notificaciones_email'),
            'id_tipo_cliente' => $request->input('id_tipo_cliente'),
            'id_clientes_hermanos' => $request->input('id_clientes_hermanos') ?? $cliente->id_clientes_hermanos,
            'id_catalogo_encuesta' => $request->input('id_catalogo_encuesta') ?? $cliente->id_catalogo_encuesta,
            'documentacion_digital' => $request->input('documentacion_digital') ? 1 : 0,
            'terminos' => $request->input('terminos') ?? $cliente->terminos,
            'tipo_persona' => $request->input('tipo_persona') ?? $cliente->tipo_persona,
            'requiere_facturar' =>$request->input('requiere_facturar') ? 1 : 0,
            'rfc' => $request->input('rfc') ?? $cliente->rfc,
            'rso' => $request->input('rso') ?? $cliente->rso,
            'nombre_uno' => $request->input('nombre_uno') ?? $cliente->nombre_uno,
            'telefono_uno' => $request->input('telefono_uno') ?? $cliente->telefono_uno,
            'nombre_dos' => $request->input('nombre_dos') ?? $cliente->nombre_dos,
            'telefono_dos' => $request->input('telefono_dos') ?? $cliente->telefono_dos,
            'telefono_mobil' => $request->input('telefono_mobil') ?? $cliente->telefono_mobil,
            'calle' => $request->input('calle') ?? $cliente->calle,
            'entre_cale' => $request->input('entre_cale') ?? $cliente->entre_cale,
            'colonia' => $request->input('colonia') ?? $cliente->colonia,
            'codigo_postal' => $request->input('codigo_postal') ?? $cliente->codigo_postal,
            'ciudad' => $request->input('ciudad') ?? $cliente->ciudad,
            'estado' => $request->input('estado') ?? $cliente->estado,
            'pais' => $request->input('pais') ?? $cliente->pais,
            'rason_social' => $request->input('rason_social') ?? $cliente->rason_social,
            'habilitar_resumen' => $request->input('habilitar_resumen') ? 1 : 0,
            'habilitar_alta_familias' => $request->input('habilitar_alta_familias') ? 1 : 0,
            'habilitar_logo' => $request->input('habilitar_logo') ? 1 : 0
        ]);

        // Manejo de la subida de archivos (logo)
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logoNombre = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $rutaLogo = "clientes/{$cliente->id}/logo/";

            // Eliminar logo anterior si existe
            if ($cliente->ubicacion_logo && Storage::disk('public')->exists($cliente->ubicacion_logo)) {
                Storage::disk('public')->delete($cliente->ubicacion_logo);
            }

            // Guardar nuevo logo
            $file->storeAs($rutaLogo, $logoNombre, 'public');
            $cliente->update(['ubicacion_logo' => $rutaLogo . $logoNombre]);
        }
        $errorLink = null;
        $newLink = null;
        if ($request->input('habilitar_alta_familias') == 1) {
            $anioActual = Carbon::now()->year;
            $proyecto = Proyectos::with(['clientes'])->where('borrado', 0)->where('activo', 1)->where('anio_proyecto', $anioActual)->first();

            if (empty($proyecto)) {
                $errorLink = 'Cliente no asignado a proyecto en curso.';
                //return response()->json(['message' => 'Cliente modificado', 'data' => $cliente, 'tokenData' => null, 'error_link' => 'Cliente no asignado a proyecto en curso.' ], 200);;
            } else {
                $proyectoClientes = $proyecto['clientes'];
                $arrayClientes = [];
                foreach($proyectoClientes as $pc){
                    array_push($arrayClientes, $pc['id']);
                }
                //return response()->json(['data'=> $arrayClientes], 400);
                if (in_array($id, $arrayClientes)) {
                    
                    $ordenServicio = OrdenesServicio::where('id_cliente', $id)->where('id_proyecto', $proyecto->id)->where('activo', 1)->first();
                    if (!empty($ordenServicio)) {
                        $resTk = $this->linkGenerarToken($id);
                        //return response()->json([$resTk], 400);
                        $newLink = RegistroToken::create([
                            'id_cliente'        => $id,
                            'id_proyecto'       => $proyecto->id,
                            'id_orden_servicio' => $ordenServicio->id,
                            'token'             => $resTk['token'],
                            'token_parte1'      => $resTk['token1'],
                            'token_parte2'      => $resTk['token2'],
                            'link_registro' => $resTk['link']
                        ]);
                    } else {
                        $errorLink = 'Se necesita asignar una Orden de servicio al cliente.';
                    }
                    //dd($ordenServicio);
                }
            }
           
        }

        return response()->json(['message' => 'Cliente modificado', 'data' => $cliente, 'tokenData' => $newLink ?? null, 'error_link' => $errorLink ], 200);
    }

    public function getLinkRegistro($id){
        $cliente = Clientes::with('proyectos')->find($id);
        $proyecto = $cliente->proyectos[0];
        //return response()->json($cliente);
        $linkData = RegistroToken::where('id_cliente', $cliente->id)->where('id_proyecto', $proyecto->id)->first();

        return response()->json($linkData);
    }

    public function editarConfiguraciones(Request $request){

        $dataEditConfig = $request->all();
        $cuenta = User::with('cliente')->find(auth()->id());  //auth()->id();
        $cliente = Clientes::findOrFail($cuenta->id_cliente);
        $cliente->update([
            'documentacion_digital' => $request->input('documentacion_digital') ? 1 : 0,
            'requiere_facturar' =>$request->input('requiere_facturar') ? 1 : 0,
            'habilitar_resumen' => $request->input('habilitar_resumen') ? 1 : 0,
            'habilitar_alta_familias' => $request->input('habilitar_alta_familias') ? 1 : 0,
            'habilitar_logo' => $request->input('habilitar_logo') ? 1 : 0,
        ]);
        //$cliente->save();
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logoNombre = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $rutaLogo = "clientes/{$cliente->id}/logo/";

            // Eliminar logo anterior si existe
            if ($cliente->ubicacion_logo && Storage::disk('public')->exists($cliente->ubicacion_logo)) {
                Storage::disk('public')->delete($cliente->ubicacion_logo);
            }

            // Guardar nuevo logo
            $file->storeAs($rutaLogo, $logoNombre, 'public');
            $cliente->update(['ubicacion_logo' => $rutaLogo . $logoNombre]);
        }
        
        return response()->json(['message' => 'Cliente modificado', 'data' => $dataEditConfig], 200);
    }

    public function getConfiguraciones(){
        $cuenta = User::with('cliente')->find(auth()->id());
        $data = Clientes::where('id', $cuenta->id_cliente)->first();
        if(empty($data)){
            return response()->json($data, 200);
        } else {
            return response()->json($data, 200);
        }
        
    }

    public function clienteUsuarioEmpresa(Request $request)
    {

        $user = auth()->user();
        $id_cliente = $user->id_cliente;
        $perfil_nombre = $user->perfil->nombre;

        if ($perfil_nombre !== "Empresas") {
            return response()->json([]);
        }

        $cliente = Clientes::find($id_cliente);

        return  response()->json($cliente);
    }

    public function linkRegistroGenerar($id){
        return response()->json(['id' =>$id]);
    }

    public function linkGenerarToken($id){
        $link = 'http://localhost:3000/registro/';
        $customClaims = [
            'iss' => "sinergia", // emisor
            'iat' => time(), // fecha de creación
            'exp' => strtotime('+1 month'), // expiración (1 hora)
            'cliente_id' => $id, // puedes poner el ID o dato que necesites
        ];

        $payload = JWTAuth::factory()->make($customClaims);

        $tokenJWT = JWTAuth::encode($payload)->get();

        $halfLength = strlen($tokenJWT) / 2;
        $tokenPart1 = substr($tokenJWT, 0, (int) $halfLength);
        $tokenPart2 = substr($tokenJWT, (int) $halfLength);

        $linkRegistro = $link . $tokenPart1;
        return ['link' => $linkRegistro, 'token'=> $tokenJWT, 'token1' => $tokenPart1, 'token2' => $tokenPart2];
    }

    public function getLogo(Request $request){
        //$path = storage_path("app/public/clientes/$id/logo/1743805376_test_conexion_ftp_1.png");
        $datos = $request->all();
        $path = storage_path("app/public/" . $datos['logo'] );
        //return response()->json($datos);
        if (!file_exists($path)) {
            return response()->json(['error' => 'No encontrado'], 404);
        }

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return response()->json(['base64' => $base64]);
    }
}
