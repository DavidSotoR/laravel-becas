<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoCatalogoEncuestasPreguntas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogo_encuestas_preguntas', function (Blueprint $table) {
            $table->unsignedBigInteger('orden')->default(0);
            $table->unsignedBigInteger('numero_pregunta')->nullable();
            $table->unsignedBigInteger('longitud_respuesta')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalogo_encuestas_preguntas', function (Blueprint $table) {
            $table->dropColumn('orden');
            $table->dropColumn('numero_pregunta');
            $table->dropColumn('longitud_respuesta');
        });
    }
}
