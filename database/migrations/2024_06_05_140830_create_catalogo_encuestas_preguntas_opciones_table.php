<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogoEncuestasPreguntasOpcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_encuestas_preguntas_opciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_catalogo_encuesta_pregunta');
            $table->timestamps();
            $table->foreign('id_catalogo_encuesta_pregunta','id_catalogo_encuesta_pregunta_foreign')->references('id')->on('catalogo_encuestas_preguntas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogo_encuestas_preguntas_opciones');
    }
}
