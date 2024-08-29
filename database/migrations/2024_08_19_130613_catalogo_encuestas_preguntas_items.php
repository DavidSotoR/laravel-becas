<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CatalogoEncuestasPreguntasItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_encuestas_preguntas_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_catalogo_encuestas_pregunta');
            $table->string('descripcion',250)->nullable();
            $table->string('texto',1200)->nullable();
            $table->integer('valor')->nullable();
            //$table->integer('valor',250)->nullable();
            $table->integer('puntos')->default(0);

            $table->foreign('id_catalogo_encuestas_pregunta','id_catalogo_encuestas_preguntas_cepi_fk')->references('id')->on('catalogo_encuestas_preguntas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogo_encuestas_preguntas_items');
    }
}
