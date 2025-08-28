<?php

namespace App\Http\Controllers;

use App\Proyectos;
use App\Clientes;
use App\OrdenesServicio;
use App\ProyectosClientes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
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
        if (isset($request->activo) && $request->activo === 'borrado') {
            $query->where('borrado', 1);
        } else {
            if(isset($request->id_tipo_cliente)){
                $query->where('id_tipo_cliente', $request->id_tipo_cliente);
            }
    
            if(isset($request->activo) && $request->activo !== 'all'){
                $query->where('activo', $request->activo);
            }
    
            $query->where('borrado', 0);
        }
       
        $lista = $query->get();
        return response()->json($lista);
    }

    public function listaFiltro(Request $request){
        $query = Proyectos::query()->with('tipoCliente');
        $query->where('borrado', 0);
        $query->where('activo', 1);
        $lista = $query->get();
        return response()->json($lista);
    }

    public function id($id){
        $elemento = Proyectos::with(['tipoCliente','clientes'])->where('id',$id)->first();
        return response()->json($elemento);
    }

    public function nuevo(Request $request){

        $validator = Validator::make($request->all(), [
        'activo' => 'boolean',
        'nombre' => 'required|string|unique:proyectos',
        'anio_proyecto' => [
            'required',
                Rule::unique('proyectos', 'anio_proyecto')
                    ->where(function ($query) use ($request) {
                        // Si la empresa es 2, no aplicamos la restricción de unicidad
                        if ($request->id_empresa == 2) {
                            return $query; // devuelve el query "en crudo", sin condiciones extras
                        }

                        // Para las demás empresas sí validamos por cliente y borrado
                        return $query->where('borrado', 0)
                                    ->where('id_tipo_cliente', $request->id_tipo_cliente);
                    }),
            ],
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
            'anio_proyecto' => [
                'required',
                Rule::unique('proyectos', 'anio_proyecto')
                ->ignore($id)
                ->where(function ($query) {
                    return $query->where('borrado', 0); // Solo valida contra proyectos no borrados
                }),
            ],
            'id_tipo_cliente' => 'required|int',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $editar = Proyectos::where('id',$id)->first();
        $editar->activo = $request->activo;
        $editar->nombre = $request->nombre;
        $editar->id_tipo_cliente = $request->id_tipo_cliente;
        $editar->anio = $request->anio;
        $editar->anio_proyecto = $request->anio_proyecto;
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

    public function borrarProyecto(Request $request){
        $id = $request->id;
        $validator = Validator::make($request->all(),[
            'anio_proyecto' => [
                'required',
                Rule::unique('proyectos', 'anio_proyecto')->ignore($id)
            ],
        ]);

        $proyecton_tiene_clientes_asignados = ProyectosClientes::where('id_proyecto',$request->id)->count();

        if($proyecton_tiene_clientes_asignados){
            return response()->json(["errors"=> "Este proyecto no se puede eliminar porque tiene clientes asignados."], 400);
        }
        $editar = Proyectos::find($request->id);
        $editar->borrado = 1;
        $editar->borrado_user = Auth::id();
        $editar->borrado_fecha = Carbon::now();

        $editar->save();
        return response()->json([$editar], 201);
    }

    public function reintegrarProyecto(Request $request){
        $proyectoReintegrar = Proyectos::find($request->id);
        if ($proyectoReintegrar->anio_proyecto === null || $proyectoReintegrar->anio_proyecto === '') {
            $proyectoReintegrar->borrado = 0;
            $proyectoReintegrar->save();
            return response()->json(['message'=>'Proyecto "'. $proyectoReintegrar->nombre . '" reintegrado.', 'proyecto' => $proyectoReintegrar]);
        }

        $coincidenciasProyectos = Proyectos::where('anio_proyecto', $request->anio_proyecto)->where('borrado', 0)->first();

        if (empty($coincidenciasProyectos)) {
            $proyectoReintegrar->borrado = 0;
            $proyectoReintegrar->save();
            return response()->json(['message'=>'Proyecto "'. $proyectoReintegrar->nombre . '" reintegrado.', 'proyecto' => $proyectoReintegrar]);
        }

        return response()->json([
                'message'=>'Error al reincorporar proyecto. El proyecto: '. $coincidenciasProyectos->nombre . ' esta asignado con el mismo año.' , 
                'proyecto' => $proyectoReintegrar]);
    } 
}
