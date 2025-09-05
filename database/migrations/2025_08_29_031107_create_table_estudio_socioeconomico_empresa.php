<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEstudioSocioeconomicoEmpresa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudio_socioeconomico_empresa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_sucursal');
            $table->unsignedBigInteger('id_formato_estudio');
            $table->string('contacto');
            $table->string('email');
            $table->string('telefono');
            $table->string('nombre');
            $table->string('departamento');
            $table->string('descripcion');
            $table->string('titulo');
            $table->boolean('activo');
            $table->date('fecha_entrega');
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
        Schema::dropIfExists('estudio_socioeconomico_empresa');
    }
}
