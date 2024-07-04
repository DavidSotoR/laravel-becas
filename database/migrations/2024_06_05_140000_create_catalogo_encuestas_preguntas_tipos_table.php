<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogoEncuestasPreguntasTiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_encuestas_preguntas_tipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',250);
            $table->string('descripcion',1200)->nullable();
            $table->timestamps();
        });

        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['nombre' => 'Rango numerico']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['nombre' => 'Opciones solo una']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['nombre' => 'Seleccion multiple acumulativo']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['nombre' => 'Seleccion multiple mayor valor']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['nombre' => 'Pregunta abierta']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogo_encuestas_preguntas_tipos');
    }
}
