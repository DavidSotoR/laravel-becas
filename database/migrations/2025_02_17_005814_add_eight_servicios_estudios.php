<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEightServiciosEstudios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicios_estudios', function (Blueprint $table) {
            $table->integer('aniadir_observaciones')->boolean()->default(0)->nullable();
            $table->string('observaciones',1200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicios_estudios', function (Blueprint $table) {
            $table->dropColumn('aniadir_observaciones');
            $table->dropColumn('observaciones');
        });
    }
}
