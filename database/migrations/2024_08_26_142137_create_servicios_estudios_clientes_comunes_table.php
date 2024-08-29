<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosEstudiosClientesComunesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios_estudios_clientes_comunes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_servicio_estudio');
            $table->unsignedBigInteger('id_cliente');
            $table->foreign('id_servicio_estudio')->references('id')->on('servicios_estudios')->onDelete('cascade');
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade');
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
        Schema::dropIfExists('servicios_estudios_clientes_comunes');
    }
}
