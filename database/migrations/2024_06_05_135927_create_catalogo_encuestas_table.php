<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogoEncuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_encuestas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tipo_cliente');
            $table->string('nombre',250);
            $table->string('descripcion',1200)->nullable();
            $table->timestamps();
            $table->foreign('id_tipo_cliente','id_tipo_cliente_foreign')->references('id')->on('tipos_clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogo_encuestas');
    }
}
