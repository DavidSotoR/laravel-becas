<?php

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
    //Perfiles
    Route::get('perfiles', 'PerfilesController@lista');
    Route::get('perfiles/{id}', 'PerfilesController@id');

    //Tipos de Clientes
    Route::get('clientes/tipos', 'TiposClientesController@lista');

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
    Route::get('clientes/{id}/usuarios', 'ClientesController@usuarios');
    Route::get('clientes/{id}', 'ClientesController@id');
    Route::post('clientes', 'ClientesController@nuevo');
    Route::put('clientes', 'ClientesController@editar');
    Route::get('clientes/{id_cleinte}/ordenes-servicio', 'ClientesController@ordenesServicio');

    //Ciclos Escolares
    Route::get('ciclos', 'CicloEscolarController@lista');
    Route::get('ciclos/{id}', 'CicloEscolarController@id');
    Route::post('ciclos', 'CicloEscolarController@nuevo');
    Route::put('ciclos', 'CicloEscolarController@editar');

    //Proyectos
    Route::get('proyectos', 'ProyectosController@lista');
    Route::get('proyectos/{id}', 'ProyectosController@id');
    Route::post('proyectos', 'ProyectosController@nuevo');
    Route::put('proyectos', 'ProyectosController@eliminar');
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
    Route::post('proyectos/clientes/ordenes-servicio', 'OrdenesServicioController@nuevo');
    Route::put('proyectos/clientes/ordenes-servicio/{id}', 'OrdenesServicioController@editar');


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
    Route::get('familias/documentos/tipos', 'FamiliasDocumentosTiposController@lista');
    Route::get('familias/{id_familia}/documentos', 'FamiliasDocumentosController@lista');
    Route::get('familias/documentos/file/{alias}', 'FamiliasDocumentosController@file');
    Route::get('familias/documentos/{id}', 'FamiliasDocumentosController@id');
    Route::post('familias/documentos', 'FamiliasDocumentosController@nuevo');

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

    Route::get('catalogos/encuestas', 'CatalogoEncuestasController@lista');
    Route::get('catalogos/encuestas/{id}', 'CatalogoEncuestasController@id');
    Route::post('catalogos/encuestas', 'CatalogoEncuestasController@nuevo');
    Route::put('catalogos/encuestas', 'CatalogoEncuestasController@editar');

    Route::get('estudio/colaboradores', 'UsuariosController@colaboradores');
    Route::post('estudio/{id_estudio}/colaboradores', 'ServicioEstudioController@asignarColaborador');
    Route::get('estudio/{id_estudio}/encuesta', 'ServicioEstudioController@encuesta');
    Route::post('estudio/colaboradores', 'ServicioEstudioController@asignarColaboradores');
    Route::get('estudio/socioeconomico', 'ServicioEstudioController@lista');
    Route::get('estudio/socioeconomico/{id}', 'ServicioEstudioController@id');
    Route::post('estudio/socioeconomico', 'ServicioEstudioController@rejistroSocioeconomico');


});
/*
Route::post('pwreturn',function(Request $request){
    return response()->json([bcrypt($request->password)],201);
});
*/
