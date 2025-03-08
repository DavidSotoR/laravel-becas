<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOneServiciosEstudiosRespuestasClasificacions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('servicios_estudios_respuestas_clasificacions')->insert(
            [
                'id'=> 5,
                //'id_catalogo_encuestas_pregunta'=> 1,
                'nombre' => 'Otros'
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
