<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFourServiciosEstudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicios_estudios', function (Blueprint $table) {
            $table->unsignedBigInteger('id_calidad')->nullable();
            $table->foreign('id_calidad')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('id_gerencia')->nullable();
            $table->foreign('id_gerencia')->references('id')->on('users')->onDelete('cascade');
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
            $table->dropForeign(['id_calidad']);
            $table->dropColumn('id_calidad');
            $table->dropForeign(['id_gerencia']);
            $table->dropColumn('id_gerencia');
        });
    }
}
