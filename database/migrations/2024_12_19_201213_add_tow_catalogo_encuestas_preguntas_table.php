<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTowCatalogoEncuestasPreguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogo_encuestas_preguntas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_parametro_clasificacion_parametro_adicional_uno')->nullable();
            $table->foreign('id_parametro_clasificacion_parametro_adicional_uno','id_parametro_clasificacion_parametro_adicional_uno_forean')->references('id')->on('catalogo_encuestas_preguntas_parametros_clasificaciones');
            $table->unsignedBigInteger('id_parametro_clasificacion_parametro_adicional_dos')->nullable();
            $table->foreign('id_parametro_clasificacion_parametro_adicional_dos','id_parametro_clasificacion_parametro_adicional_dos_forean')->references('id')->on('catalogo_encuestas_preguntas_parametros_clasificaciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalogo_encuestas_preguntas', function (Blueprint $table) {
            $table->dropForeign('id_parametro_clasificacion_parametro_adicional_uno_forean');
            $table->dropColumn('id_parametro_clasificacion_parametro_adicional_uno');
            $table->dropForeign('id_parametro_clasificacion_parametro_adicional_dos_forean');
            $table->dropColumn('id_parametro_clasificacion_parametro_adicional_dos');
        });
    }
}
