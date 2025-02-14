<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogoEncuestasRangosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_encuestas_rangos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_catalogo_encuesta');
            $table->integer('limite_superior');
            $table->integer('limite_inferior');
            $table->integer('porcentaje_sujerido');
            $table->foreign('id_catalogo_encuesta','id_catalogo_encuesta_rangos_fk')->references('id')->on('catalogo_encuestas');
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
        Schema::dropIfExists('catalogo_encuestas_rangos');
    }
}
