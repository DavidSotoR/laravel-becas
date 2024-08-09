<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThreeCatalogoEncuestasPreguntas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogo_encuestas_preguntas', function (Blueprint $table) {
            $table->dropForeign('id_catalogo_encuestas_preguntas_parametro_clasificacion_fk');
            $table->dropColumn('id_catalogo_encuestas_preguntas_parametro_clasificacion');

            $table->integer('puntos_maximos')->default(0)->change();
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
            $table->integer('puntos_maximos')->default(null)->nullable(false)->change();
        });
    }
}
