<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOneCatalogoEncuestasPreguntasParametrosClasificacionsTiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('catalogo_encuestas_preguntas_parametros_clasificacions_tipos')->insert(
            ['id'=> 3,'nombre' => 'Coincidencia']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('catalogo_encuestas_preguntas_parametros_clasificacions_tipos')->delete(3);
    }
}
