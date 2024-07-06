<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogoEncuestasPreguntasParametrosClasificacionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_encuestas_preguntas_parametros_clasificacion_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_catalogo_encuestas_preguntas_parametro_clasificacion');
            $table->unsignedBigInteger('id_catalogo_encuestas_preguntas')->nullable();
            $table->string('texto',250)->nullable();
            $table->integer('limite_superior')->nullable();
            $table->integer('limiten_inferior')->nullable();
            $table->string('valor',250);
            $table->foreign('id_catalogo_encuestas_preguntas_parametro_clasificacion','id_catalogo_encuestas_preguntas_parametro_clasificacion_foreign')->references('id')->on('catalogo_encuestas_preguntas_parametros_clasificaciones');
            $table->foreign('id_catalogo_encuestas_preguntas','id_catalogo_encuestas_preguntas_fkwedsa')->references('id')->on('catalogo_encuestas_preguntas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogo_encuestas_preguntas_parametros_clasificacion_items');
    }
}
