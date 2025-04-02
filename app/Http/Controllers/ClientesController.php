<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Clientes;
use App\OrdenesServicio;
use App\ProyectosClientes;
use Illuminate\Support\Facades\Storage;

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
        $elemento = Clientes::with("tipoCliente")->where('id', $id)->first();
        return response()->json($elemento);
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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Validación del logo
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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Manejo de archivos
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
            'habilitar_resumen' => $request->input('habilitar_resumen') ? 1 : 0
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

        return response()->json(['message' => 'Cliente modificado', 'data' => $cliente], 200);
    }

    public function editarConfiguraciones(Request $request){
        return response()->json(['message' => 'Cliente modificado', 'data' => $request->data()], 200);
    }

    public function getConfiguraciones(Request $request){
        $data = Clientes::where('id', $request->id_cliente)->first();
        if(!empty($data)){
            return response()->json(['message' => 'Cliente no encontrado.', 'data' => $data], 200);
        } else {
            return response()->json(['message' => 'Cliente encontrado.', 'data' => $data], 200);
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
}
