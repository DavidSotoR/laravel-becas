<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoServiciosEstudiosRespuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicios_estudios_respuestas', function (Blueprint $table) {
            $table->integer('porcentaje_otorgado')->default(0);
            $table->integer('numero_familia')->nullable();
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
            $table->dropColumn('porcentaje_otorgado');
            $table->dropColumn('numero_familia');
        });
    }
}
