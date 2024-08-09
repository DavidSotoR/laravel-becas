<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFourCatalogoEncuestasPreguntas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogo_encuestas_preguntas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_catalogo_encuestas_preguntas_parametro_clasificacion')->nullable();
            $table->foreign('id_catalogo_encuestas_preguntas_parametro_clasificacion','id_catalogo_encuestas_preguntas_parametro_clasificacion_fk')->references('id')->on('catalogo_encuestas_preguntas_parametros_clasificaciones');
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
            $table->unsignedBigInteger('id_catalogo_encuestas_preguntas_parametro_clasificacion')->nullable(false)->change();
        });
    }
}
