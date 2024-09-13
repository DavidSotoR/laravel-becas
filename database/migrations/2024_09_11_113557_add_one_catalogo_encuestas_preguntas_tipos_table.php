<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOneCatalogoEncuestasPreguntasTiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 20 , 'nombre' => 'Actualmente con empleo']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('catalogo_encuestas_preguntas_tipos')->where('id', 20)->delete();
    }
}
