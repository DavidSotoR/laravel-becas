<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosEstudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios_estudios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_servicio_estado')->default(1);
            $table->unsignedBigInteger('id_proyecto')->nullable();
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_orden_servicio')->nullable();
            $table->unsignedBigInteger('id_colaborador')->nullable();
            $table->boolean('es_cliente_comun')->default(false);
            $table->string('candidato',240);
            $table->string('situacion',500)->nullable();
            $table->string('email',120)->nullable();
            $table->string('telefono_movil',18)->nullable();
            $table->string('telefono_contacto',18)->nullable();
            $table->string('curp',18)->nullable();
            $table->string('domicilio',240)->nullable();
            $table->string('entrecalles',240)->nullable();
            $table->string('departamento',240)->nullable();
            $table->string('anterior_empleo',120)->nullable();
            $table->string('anterior_puesto',120)->nullable();
            $table->string('anterior_empresa',120)->nullable();
            $table->string('anterior_antiguedad',120)->nullable();
            $table->timestamps();
            $table->foreign('id_servicio_estado')->references('id')->on('servicio_estados')->onDelete('cascade');
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('id_orden_servicio')->references('id')->on('ordenes_servicio')->onDelete('cascade');
            $table->foreign('id_proyecto')->references('id')->on('proyectos')->onDelete('cascade');
            $table->foreign('id_colaborador')->references('id')->on('users')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicios_estudios');
    }
}
