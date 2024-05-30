<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_familias');
            $table->unsignedBigInteger('id_ciclo_escolar');
            $table->string('nombre',120);
            $table->string('domicilio',240);
            $table->string('colonia',60);
            $table->string('municipio',60);
            $table->string('codigo_postal',5);
            $table->string('telefono_madre',60)->nullable();
            $table->string('telefono_padre',60)->nullable();
            $table->timestamps();
            $table->foreign('id_familias')->references('id')->on('familias');
            $table->foreign('id_ciclo_escolar')->references('id')->on('ciclo_escolar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumnos');
    }
}
