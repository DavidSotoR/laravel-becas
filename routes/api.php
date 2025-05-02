<?php

use App\Clientes;
use App\OrdenesServicio;
use App\Proyectos;
use App\RegistroToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/* Route::get('clientes/{id}/link/registro', 'ClientesController@linkRegistroGenerar');
 */

Route::get('/registro/link/{token}', function ($id) {
    $cliente = Clientes::find($id);
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
            dd('tiene los parametros para generar link.');
        }
        //dd($ordenServicio);
    } else {
        dd('no existe el cliente');
    }

    $ordenServicio = OrdenesServicio::whereIn('id_cliente', $arrayClientes)->get();
    return response()->json($ordenServicio);
});

Route::get('/registro/escuela/{token}', function ($token) {
    $tokenRegistro = RegistroToken::where('token_parte1', $token)->first();
    return response()->json($tokenRegistro);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('register', 'AuthController@register');

    //Usuarios
    Route::get('usuarios', 'UsuariosController@lista');
    Route::put('usuarios', 'UsuariosController@editar');
    Route::get('usuarios/{id}', 'UsuariosController@id');
    Route::delete('usuarios/{id}', 'UsuariosController@disableOrEnable');
    Route::put('usuarios/lista/activar', 'UsuariosController@disableOrEnableList');
    Route::put('usuarios/{id}/password', 'UsuariosController@editarPassword');

    //Perfiles
    Route::get('perfiles', 'PerfilesController@lista');
    Route::get('perfiles/{id}', 'PerfilesController@id');

    //Tipos de Clientes
    Route::get('clientes/tipos', 'TiposClientesController@lista');
    Route::post('logo/clientes', 'ClientesController@getLogo');
    //Route::get('')

    //Clientes Hermanos
    Route::get('clientes/hermanos', 'ClientesHermanosController@lista');
    Route::get('clientes/{id_cliente}/hermanos', 'ClientesHermanosController@listaHermanos');
    Route::get('clientes/hermanos/{id}', 'ClientesHermanosController@id');
    Route::post('clientes/hermanos/{id}', 'ClientesHermanosController@nuevosHermanos');
    Route::post('clientes/hermanos', 'ClientesHermanosController@nuevo');
    Route::put('clientes/hermanos', 'ClientesHermanosController@editar');
    Route::delete('clientes/{id}/hermano', 'ClientesHermanosController@eliminarHermano');

    //Clientes
    Route::get('clientes', 'ClientesController@lista');
    Route::get('clientes/filtro/proyecto/{id}', 'ClientesController@listaFiltrosClientesPorRoyecto');
    Route::get('clientes/{id}/usuarios', 'ClientesController@usuarios');
    Route::get('clientes/{id}', 'ClientesController@id');
    Route::post('clientes', 'ClientesController@nuevo');
    Route::post('clientes/{id}', 'ClientesController@editar');
    Route::get('clientes/{id_cleinte}/ordenes-servicio', 'ClientesController@ordenesServicio');
    
    Route::post('cuenta/configuraciones', 'ClientesController@editarConfiguraciones');
    Route::get('cuenta/configuraciones', 'ClientesController@getConfiguraciones');
    //Ciclos Escolares
    Route::get('ciclos', 'CicloEscolarController@lista');
    Route::get('ciclos/{id}', 'CicloEscolarController@id');
    Route::post('ciclos', 'CicloEscolarController@nuevo');
    Route::put('ciclos', 'CicloEscolarController@editar');

    //Proyectos
    Route::get('proyectos', 'ProyectosController@lista');
    Route::get('proyectos/filtro', 'ProyectosController@listaFiltro');
    Route::get('proyectos/{id}', 'ProyectosController@id');
    Route::post('proyectos', 'ProyectosController@nuevo');
    Route::post('proyectos/editar', 'ProyectosController@editar');
    //Route::put('proyectos', 'ProyectosController@eliminar');
    Route::put('proyectos/borrar', 'ProyectosController@borrarProyecto');
    Route::put('proyectos/reintegrar', 'ProyectosController@reintegrarProyecto');
    //Proyectos Clientes
    Route::get('proyectos/{id_proyecto}/clientes', 'ProyectosClientesController@lista');
    Route::get('proyectos/{id_proyecto}/clientes-encuestas', 'ProyectosClientesController@clientesEncuestaLista');
    Route::get('proyectos/clientes/{id}', 'ProyectosClientesController@id');
    Route::post('proyectos/{id_proyecto}/clientes', 'ProyectosClientesController@nuevo');
    Route::delete('proyectos/clientes/{id}', 'ProyectosClientesController@eliminar');
    //Proyectos Clientes Ordenes de servicio
    //Route::get('proyectos/{id_proyecto}/clientes/{id_cliente}/ordenes-servicio', 'OrdenesServicioController@listaProyectoCliente');
    //Proyectos Ordenes de servicio
    Route::get('proyectos/{id_proyecto}/clientes/{id_cliente}/ordenes-servicio', 'OrdenesServicioController@lista');
    Route::get('proyectos/clientes/ordenes-servicio/{id}', 'OrdenesServicioController@id');
    Route::get('proyectos/clientes/ordenes-servicio/{id}/datos', 'OrdenesServicioController@idDatos');
    Route::post('proyectos/clientes/ordenes-servicio', 'OrdenesServicioController@nuevo');
    Route::put('proyectos/clientes/ordenes-servicio/{id}', 'OrdenesServicioController@editar');

    //ORDENES DE SERVICIO
    Route::get('ordenes-servicio', 'OrdenesServicioController@OrdenesServiciosCatalogo');

    //Familias
    Route::get('familias', 'FamiliasController@lista');
    Route::get('familias/{id}', 'FamiliasController@id');
    Route::post('familias', 'FamiliasController@nuevo');
    //Familias Padres Tipos
    Route::get('familias/padres/tipos', 'FamiliasPadresTipoController@lista');
    //Familias Padres
    Route::get('familias/{id_familia}/padres', 'FamiliasPadresController@lista');
    Route::get('familias/padres/{id}', 'FamiliasPadresController@id');
    Route::post('familias/padres', 'FamiliasPadresController@nuevo');
    //Familias Alumnos
    Route::get('familias/{id_familia}/alumnos', 'AlumnosController@lista');
    Route::get('familias/alumnos/{id}', 'AlumnosController@id');
    Route::post('familias/alumnos', 'AlumnosController@nuevo');
    //Familias documentos
    Route::get('familias/{id_familia}/estudio/socioeconomico', 'FamiliasController@estudioSocioeconomico');
    Route::get('familias/{id_familia}/estudio/socioeconomico/padres', 'FamiliasController@estudioSocioeconomicoPadres');
    Route::get('familias/documentos/tipos', 'FamiliasDocumentosTiposController@lista');
    Route::get('familias/{id_familia}/documentos', 'FamiliasDocumentosController@lista');
    Route::get('familias/documentos/file/{id}', 'FamiliasDocumentosController@file');
    Route::delete('familias/documentos/file/{id}', 'FamiliasDocumentosController@borrarArchivoIdFamilia');
    Route::get('familias/documentos/{id}', 'FamiliasDocumentosController@id');
    Route::post('familias/documentos', 'FamiliasDocumentosController@nuevo');
    Route::post('familias/{id_familia}/estudio/socioeconomico/padres/update', 'FamiliasController@estudioSocioeconomicoPadresUpdate');
    Route::post('familias/{id_familia}/estudio/socioeconomico/correo', 'FamiliasController@estudioSocioeconomicoEnviarCorreo');

    //Encuestas

    Route::get('catalogos/encuestas/parametros/items/{id}', 'CatalogoEncuestasPreguntasParametrosClasificacionItemsController@id');
    Route::get('catalogos/encuestas/parametros/items', 'CatalogoEncuestasPreguntasParametrosClasificacionItemsController@lista');
    Route::get('catalogos/encuestas/parametros/{id_parametros}/items', 'CatalogoEncuestasPreguntasParametrosClasificacionItemsController@lista');
    Route::get('catalogos/encuestas/parametros/{id_parametros}/preguntas/{id_pregunta}/items', 'CatalogoEncuestasPreguntasParametrosClasificacionItemsController@lista');
    Route::post('catalogos/encuestas/parametros/items', 'CatalogoEncuestasPreguntasParametrosClasificacionItemsController@nuevo');
    Route::put('catalogos/encuestas/parametros/items/{id}', 'CatalogoEncuestasPreguntasParametrosClasificacionItemsController@editar');
    Route::delete('catalogos/encuestas/parametros/items/{id}', 'CatalogoEncuestasPreguntasParametrosClasificacionItemsController@eliminar');

    Route::get('catalogos/encuestas/parametros/tipos/{id}', 'CatalogoEncuestasPreguntasParametrosClasificacionsTipoController@id');
    Route::get('catalogos/encuestas/parametros/tipos', 'CatalogoEncuestasPreguntasParametrosClasificacionsTipoController@lista');

    Route::get('catalogos/encuestas/parametros/{id}', 'CatalogoEncuestasPreguntasParametrosClasificacionsController@id');
    Route::post('catalogos/encuestas/parametros', 'CatalogoEncuestasPreguntasParametrosClasificacionsController@nuevo');
    Route::post('catalogos/encuestas/preguntas/parametros', 'CatalogoEncuestasPreguntasController@setTipoParametro');
    Route::put('catalogos/encuestas/{id_encuesta}/parametros/{id}', 'CatalogoEncuestasPreguntasParametrosClasificacionsController@editar');
    Route::get('catalogos/encuestas/{id_catalogo_encuesta}/parametros', 'CatalogoEncuestasPreguntasParametrosClasificacionsController@lista');

    Route::get('catalogos/encuestas/preguntas/tipos', 'CatalogoEncuestasPreguntasTiposController@lista');
    Route::get('catalogos/encuestas/preguntas/tipos/{id}', 'CatalogoEncuestasPreguntasTiposController@id');

    Route::post('catalogos/encuestas/preguntas', 'CatalogoEncuestasPreguntasController@nuevo');
    Route::put('catalogos/encuestas/{id_encuesta}/preguntas/{id}', 'CatalogoEncuestasPreguntasController@editar');
    Route::get('catalogos/encuestas/{id_encuesta}/preguntas', 'CatalogoEncuestasPreguntasController@lista');
    Route::get('catalogos/encuestas/preguntas/{id}', 'CatalogoEncuestasPreguntasController@id');

    Route::get('catalogos/encuestas/preguntas/{id_preguntas}/items', 'CatalogoEncuestasPreguntasItemsController@lista');
    Route::post('catalogos/encuestas/preguntas/{id_preguntas}/items', 'CatalogoEncuestasPreguntasItemsController@nuevo');
    Route::put('catalogos/encuestas/preguntas/{id_preguntas}/items', 'CatalogoEncuestasPreguntasItemsController@editar');
    Route::delete('catalogos/encuestas/preguntas/{id_preguntas}/items', 'CatalogoEncuestasPreguntasItemsController@eliminar');
    Route::get('catalogos/encuestas/preguntas/{id_pregunta}/parametros', 'CatalogoEncuestasPreguntasController@parametros');


    Route::post('catalogos/encuestas/rangos', 'CatalogoEncuestasRangosController@nuevo');
    Route::put('catalogos/encuestas/rangos', 'CatalogoEncuestasRangosController@editar');
    Route::get('catalogos/encuestas/{id_encuesta}/rangos', 'CatalogoEncuestasRangosController@lista');
    Route::delete('catalogos/encuestas/rangos/{id}', 'CatalogoEncuestasRangosController@eliminar');

    Route::get('catalogos/encuestas', 'CatalogoEncuestasController@lista');
    Route::get('catalogos/encuestas/{id}', 'CatalogoEncuestasController@id');
    Route::post('catalogos/encuestas/{id}/copia', 'CatalogoEncuestasController@copia');
    Route::post('catalogos/encuestas', 'CatalogoEncuestasController@nuevo');
    Route::put('catalogos/encuestas', 'CatalogoEncuestasController@editar');

    Route::get('estudio/colaboradores', 'UsuariosController@colaboradores');
    Route::get('estudio/colaboradores/asignar', 'UsuariosController@listaColaboradoresAsignar');
    Route::get('estudio/calidad', 'UsuariosController@calidad');
    Route::post('estudio/{id_estudio}/colaboradores', 'ServicioEstudioController@asignarColaborador');
    Route::get('estudio/{id_estudio}/encuesta', 'ServicioEstudioController@encuesta');
    Route::get('estudio/socioeconomico', 'ServicioEstudioController@lista');
    Route::get('estudio/socioeconomico/pdf', 'ServicioEstudioController@estudioSocioeconomicoDownloadZip');
    Route::get('estudio/socioeconomico/{id}', 'ServicioEstudioController@id');
    Route::post('estudio/socioeconomico', 'ServicioEstudioController@rejistroSocioeconomico');
    Route::post('estudio/socioeconomico/{id}/visita', 'ServicioEstudioController@addFechaVisita');
    Route::post('estudio/socioeconomico/carga/familias', 'ServicioEstudioController@cargaMasivaFamilias');
    Route::post('estudio/socioeconomico/editar/familia', 'ServicioEstudioController@editarFamiliaEstudioServicio');
    Route::get('estudio/socioeconomico/formatoalta/descargar', 'ServicioEstudioController@descargarFormatoAltaFamiliasMasiva');
    Route::post('estudio/socioeconomico/reporte/familias/parametros', 'ServicioEstudioController@resultadosEstudiosSocieconomicosReporte');

    Route::post('estudio/socioeconomico/enviar/correos', 'ServicioEstudioController@envioDeCorreosPorcentajes');

    Route::get('estudio/socioeconomico/pregunta/parametro/{id_pregunta}/adicional-uno/items', 'ServicioEstudioController@parametroAdicionalUnoItems');
    Route::get('estudio/socioeconomico/pregunta/parametro/{id_pregunta}/adicional-dos/items', 'ServicioEstudioController@parametroAdicionalDosItems');

    Route::post('estudio/preasignacion', 'ServicioEstudioController@preasignarEstudios');
    Route::post('estudio/asignar', 'ServicioEstudioController@asignarEstudios');
    Route::post('estudio/calidad', 'ServicioEstudioController@asignarCalidad');
    Route::get('estudio/socioeconomico/{id_estudio}/preguntas/{id_pregunta}/parametros/sumantria/b', 'ServicioEstudioController@getSumatoruaB');

    Route::get('estudios/enproceso/estados', 'ServicioEstadosController@listaEnProceso');
    Route::get('estudios/enproceso', 'ServicioEstudioController@listaEnProceso');
    Route::get('estudios/concluidos/proyecto/{id_proyecto}', 'ServicioEstudioController@listaConcluidos');

    Route::get('estudio/socioeconomico/{id}/encuesta', 'ServicioEstudioController@estudioSocioeconomico');
    Route::get('estudio/socioeconomico/{id}/pdf', 'ServicioEstudioController@estudioSocioeconomicoPDF');
    Route::post('estudio/observaciones','ServicioEstudioController@aniadirObservacion');

    Route::get('estudio/{id_estudio}/pregunta/{id_pregunta}/respuestas', 'ServiciosEstudiosRespuestasController@lista');
    Route::get('estudio/{id_estudio}/documentos/', 'FamiliasDocumentosController@listaFilesEstudio');

    Route::post('estudio/respuestas', 'ServiciosEstudiosRespuestasController@nuevo');
    Route::get('estudio/{id_estudio}/parametros/puntos', 'ServicioEstudioController@estudioSocioeconomicoParametrosPuntos');
    Route::get('estudio/{id_estudio}/rangos', 'ServicioEstudioController@estudioSocioeconomicoRangos');
    Route::post('estudio/{id_estudio}/porcentaje', 'ServicioEstudioController@estudioSocioeconomicoProcentaje');
    Route::post('estudio/{id_estudio}/no-familia-colegio', 'ServicioEstudioController@estudioSocioeconomicoClaveFamilia');

    Route::get('estudios/proyectos', 'ProyectosController@listaProyectosXPerfil');
    Route::get('estudios/proyectos/{id_proyecto}', 'ProyectosController@proyectoIDEmpresa');
    Route::get('estudios/proyectos/{id_proyecto}/ordenesdeservicio', 'OrdenesServicioController@listaODPEmpresa');
    Route::get('usuario/cliente', 'ClientesController@clienteUsuarioEmpresa');

    Route::get('estudios/socioeconomico/proyectos/{id_proyecto}/distribucion-del-gasto', 'EstudiosSocioeconomicosReportesController@distribucionDelGasto');
    Route::get('estudios/socioeconomico/proyectos/{id_proyecto}/rango-ingreso-mensual', 'EstudiosSocioeconomicosReportesController@gastosPorRangoIngresoMensual');


});
/*
Route::post('pwreturn',function(Request $request){
    return response()->json([bcrypt($request->password)],201);
});
*/
