<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProyectosClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyectos_clientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_proyecto');
            $table->unsignedBigInteger('id_cliente');
            $table->foreign('id_proyecto')->references('id')->on('proyectos');
            $table->foreign('id_cliente')->references('id')->on('clientes');
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
        Schema::dropIfExists('proyectos_clientes');
    }
}
