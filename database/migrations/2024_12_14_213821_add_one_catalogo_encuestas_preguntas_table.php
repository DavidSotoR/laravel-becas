<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOneCatalogoEncuestasPreguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogo_encuestas_preguntas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_parametro_clasificacion_tipo')->nullable();
            $table->foreign('id_parametro_clasificacion_tipo','id_parametro_clasificacion_tipo_forean')->references('id')->on('catalogo_encuestas_preguntas_parametros_clasificacions_tipos');
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
            $table->dropForeign(['id_parametro_clasificacion_tipo']);
            $table->dropColumn('id_parametro_clasificacion_tipo');
        });
    }
}
