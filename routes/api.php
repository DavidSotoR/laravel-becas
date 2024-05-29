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

    //Perfiles
    Route::get('perfiles', 'PerfilesController@lista');
    Route::get('perfiles/{id}', 'PerfilesController@id');


    //Clientes
    Route::get('clientes', 'ClientesController@lista');
    Route::get('clientes/{id}', 'ClientesController@id');
    Route::post('clientes', 'ClientesController@nuevo');
    Route::put('clientes', 'ClientesController@editar');
    //Tipos de Clientes
    Route::get('clientes/tipos', 'TiposClientesController@lista');


    //Ciclos Escolares
    Route::get('ciclos', 'CicloEscolarController@lista');
    Route::get('ciclos/{id}', 'CicloEscolarController@id');
    Route::post('ciclos', 'CicloEscolarController@nuevo');

    //Familias
    Route::get('familias', 'FamiliasController@lista');
    Route::get('familias/{id}', 'FamiliasController@id');
    Route::post('familias', 'FamiliasController@nuevo');
    //Familias documentos

});
