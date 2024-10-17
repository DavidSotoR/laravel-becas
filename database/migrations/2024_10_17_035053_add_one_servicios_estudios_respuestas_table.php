<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOneServiciosEstudiosRespuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicios_estudios_respuestas', function (Blueprint $table) {
            $table->bigInteger('padre_monto')->default(0)->change();
            $table->bigInteger('madre_monto')->default(0)->change();
            $table->bigInteger('monto')->default(0)->change();
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
            //
        });
    }
}
