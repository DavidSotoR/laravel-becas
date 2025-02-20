<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFourServiciosEstudiosRespuestas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicios_estudios_respuestas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_respuestas_clasificacions')->nullable();
            $table->foreign('id_respuestas_clasificacions','id_respuestas_clasificacions_forean')->references('id')->on('servicios_estudios_respuestas_clasificacions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicios_estudios_respuestas', function (Blueprint $table) {
            $table->dropForeign(['id_respuestas_clasificacions']);
            $table->dropColumn('id_respuestas_clasificacions');
        });
    }
}
