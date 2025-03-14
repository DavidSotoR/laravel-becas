<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablaCorreosPorcentajesEstudios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('correos_porcentajes_estudios', function (Blueprint $table) {
            $table->id();  // id autoincremental
            $table->unsignedBigInteger('id_servicio_estudio');
            $table->unsignedBigInteger('id_proyecto');
            $table->string('uniquekey'); // Se puede repetir
            $table->string('correo_contacto');
            $table->integer('contador');
            $table->timestamp('fecha_envio');
            $table->timestamp('fecha_reenvio')->nullable(); // Puede ser NULL
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
        Schema::dropIfExists('correos_porcentajes_estudios');
    }
}
