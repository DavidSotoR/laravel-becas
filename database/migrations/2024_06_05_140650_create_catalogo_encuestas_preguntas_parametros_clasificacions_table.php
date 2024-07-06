<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogoEncuestasPreguntasParametrosClasificacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_encuestas_preguntas_parametros_clasificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_catalogo_encuesta');
            $table->string('nombre',250);
            $table->integer('puntos_maximo')->default(0);
            $table->timestamps();
            $table->foreign('id_catalogo_encuesta','id_catalogo_encuesta_foreign')->references('id')->on('catalogo_encuestas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogo_encuestas_preguntas_parametros_clasificaciones');
    }
}
