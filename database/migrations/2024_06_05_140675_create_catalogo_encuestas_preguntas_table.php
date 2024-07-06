<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogoEncuestasPreguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_encuestas_preguntas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_catalogo_encuesta');
            $table->unsignedBigInteger('id_catalogo_encuestas_preguntas_tipo');
            $table->unsignedBigInteger('id_catalogo_encuestas_preguntas_parametro_clasificacion');
            $table->string('pregunta',250);
            $table->integer('puntos_maximos');
            $table->timestamps();
            $table->foreign('id_catalogo_encuesta','id_catalogo_encuesta_fk')->references('id')->on('catalogo_encuestas');
            $table->foreign('id_catalogo_encuestas_preguntas_tipo','id_catalogo_encuestas_preguntas_tipo_fk')->references('id')->on('catalogo_encuestas_preguntas_tipos');
            $table->foreign('id_catalogo_encuestas_preguntas_parametro_clasificacion','id_catalogo_encuestas_preguntas_parametro_clasificacion_fk')->references('id')->on('catalogo_encuestas_preguntas_parametros_clasificaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogo_encuestas_preguntas');
    }
}
