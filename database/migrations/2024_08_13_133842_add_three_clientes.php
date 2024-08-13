<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThreeClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_catalogo_encuesta')->nullable();
            $table->foreign('id_catalogo_encuesta','id_catalogo_encuesta_fkcl')->references('id')->on('catalogo_encuestas');
            $table->boolean('documentacion_digital')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign('id_catalogo_encuesta_fkcl');
            $table->dropColumn('id_catalogo_encuesta');
            $table->dropColumn('documentacion_digital');
        });
    }
}
