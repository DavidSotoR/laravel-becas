<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosEstudiosRespuestasClasificacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios_estudios_respuestas_clasificacions', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('id_catalogo_encuestas_pregunta');
            $table->string('nombre',120);
            $table->boolean('activo')->default(true);
            $table->string('descripcion',1200)->nullable();
            //$table->foreign('id_catalogo_encuestas_pregunta','id_catalogo_encuestas_preguntas_res_fk')->references('id')->on('catalogo_encuestas_preguntas')->onDelete('cascade');
            $table->timestamps();
        });

        DB::table('servicios_estudios_respuestas_clasificacions')->insert(
            [
                'id'=> 1,
                //'id_catalogo_encuestas_pregunta'=> 1,
                'nombre' => 'Necesidades esenciales'
            ]
        );
        DB::table('servicios_estudios_respuestas_clasificacions')->insert(
            [
                'id'=> 2,
                //'id_catalogo_encuestas_pregunta'=> 1,
                'nombre' => 'Viajes'
            ]
        );
        DB::table('servicios_estudios_respuestas_clasificacions')->insert(
            [
                'id'=> 3,
                //'id_catalogo_encuestas_pregunta'=> 1,
                'nombre' => 'Educación'
            ]
        );
        DB::table('servicios_estudios_respuestas_clasificacions')->insert(
            [
                'id'=> 4,
                //'id_catalogo_encuestas_pregunta'=> 1,
                'nombre' => 'Lujos'
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
        Schema::dropIfExists('servicios_estudios_respuestas_clasificacions');
    }
}
