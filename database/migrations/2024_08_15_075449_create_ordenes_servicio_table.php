<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenesServicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_servicio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_proyecto');
            $table->unsignedBigInteger('id_cliente');
            $table->boolean('activo')->default(true);
            $table->string('descripcion');
            $table->text('notas')->nullable();
            $table->date('fecha_estimada_entrega');
            $table->date('fecha_real_entrega')->nullable();
            $table->date('fecha_estimada_finalizacion');
            $table->date('fecha_real_finalizacion')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_proyecto')->references('id')->on('proyectos')->onDelete('cascade');
            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordenes_servicio');
    }
}
