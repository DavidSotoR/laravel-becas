<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogoEncuestasPreguntasParametrosClasificacionsTiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_encuestas_preguntas_parametros_clasificacions_tipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',250);
            $table->timestamps();
        });

        DB::table('catalogo_encuestas_preguntas_parametros_clasificacions_tipos')->insert(
            ['nombre' => 'Puntos por rangos numéricos']
        );

        DB::table('catalogo_encuestas_preguntas_parametros_clasificacions_tipos')->insert(
            ['nombre' => 'Calificar pregunta individualmente']
        );

        Schema::table('catalogo_encuestas_preguntas_parametros_clasificaciones', function($table) {
            $table->unsignedBigInteger('id_catalogo_encuestas_preguntas_parametros_clasificaciones_tipos')->nullable();
            $table->foreign('id_catalogo_encuestas_preguntas_parametros_clasificaciones_tipos','id_catalogo_encuestas_ppc_tipos_fk1')->references('id')->on('catalogo_encuestas_preguntas_parametros_clasificacions_tipos');
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
            $table->dropForeign('id_catalogo_encuestas_ppc_tipos_fk1');
            $table->dropColumn('id_catalogo_encuestas_preguntas_parametros_clasificaciones_tipos');
        });

        Schema::dropIfExists('catalogo_encuestas_preguntas_parametros_clasificacions_tipos');
    }
}
