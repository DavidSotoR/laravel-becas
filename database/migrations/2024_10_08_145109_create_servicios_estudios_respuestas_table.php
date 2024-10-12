<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosEstudiosRespuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios_estudios_respuestas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_servicio_estudio');
            $table->foreign('id_servicio_estudio')->references('id')->on('servicios_estudios')->onDelete('cascade');

            $table->unsignedBigInteger('id_catalogo_encuestas_pregunta');
            $table->foreign('id_catalogo_encuestas_pregunta','id_catalogo_encuestas_preguntas_ser_fk')->references('id')->on('catalogo_encuestas_preguntas')->onDelete('cascade');
            //$table->unsignedBigInteger('id_item');

            $table->string('seccion',120)->nullable();
            $table->string('parentesco',1200)->nullable();
            $table->string('nombre',1200)->nullable();
            $table->string('texto',1200)->nullable();
            $table->string('respuesta',1200)->nullable();
            $table->boolean('vive')->default(false);
            $table->boolean('activo')->default(false);
            $table->decimal('padre_monto', 12, 2)->default(0);
            $table->decimal('madre_monto', 12, 2)->default(0);
            $table->decimal('monto', 12, 2)->default(0);
            $table->string('valor')->unsignedBigInteger();
            $table->string('tipo',1200)->nullable();
            $table->string('marca_modelo',1200)->nullable();
            $table->integer('anio')->nullable();
            $table->string('propietario',1200)->nullable();
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
        Schema::dropIfExists('servicios_estudios_respuestas');
    }
}
