<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tipo_cliente');
            $table->string('nombre',120);
            $table->string('descripcion',1200)->nullable();
            $table->string('notificaciones_email',240)->nullable();
            $table->timestamps();
            $table->foreign('id_tipo_cliente')->references('id')->on('tipos_clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
