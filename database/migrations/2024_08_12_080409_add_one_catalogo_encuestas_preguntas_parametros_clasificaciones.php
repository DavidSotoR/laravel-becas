<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOneCatalogoEncuestasPreguntasParametrosClasificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogo_encuestas_preguntas_parametros_clasificaciones', function (Blueprint $table) {
            $table->string('color',12)->nullable()->default('#ffffff');
            $table->boolean('formato_decimales')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalogo_encuestas_preguntas_parametros_clasificaciones', function (Blueprint $table) {
            $table->dropColumn('color');
            $table->dropColumn('formato_decimales');
        });
    }
}
