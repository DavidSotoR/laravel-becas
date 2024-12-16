<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTowCatalogoEncuestasPreguntasParametrosClasificacionsTiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('catalogo_encuestas_preguntas_parametros_clasificacions_tipos')->insert(
            ['id'=> 4,'nombre' => 'Coincidencia Acumulativa']
        );

        DB::table('catalogo_encuestas_preguntas_parametros_clasificacions_tipos')->insert(
            ['id'=> 5,'nombre' => 'Seleccion Unica']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('catalogo_encuestas_preguntas_parametros_clasificacions_tipos')->delete([4,5]);
    }
}
