<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoFamiliasPadres extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('familias_padres', function (Blueprint $table) {
            $table->dropForeign(['id_familia']);
            $table->dropColumn('id_familia');

            $table->unsignedBigInteger('id_servicio_estudio');
            $table->foreign('id_servicio_estudio')->references('id')->on('servicios_estudios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('familias_padres', function (Blueprint $table) {
            $table->dropForeign(['id_servicio_estudio']);
            $table->dropColumn('id_servicio_estudio');
        });
    }
}
