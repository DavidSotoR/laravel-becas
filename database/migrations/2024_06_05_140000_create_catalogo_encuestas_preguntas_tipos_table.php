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
        });

        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 1 , 'nombre' => 'Pregunta abierta']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 2 , 'nombre' => 'Lista selección múltiple']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 3 , 'nombre' => 'Antigüedad en colegio']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 4 , 'nombre' => 'Número de Hijos']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 5 , 'nombre' => 'Orfandad']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 6 , 'nombre' => 'Dependientes Económicos']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 7 , 'nombre' => 'Económicamente activo']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 8 , 'nombre' => 'Ingreso mensual']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 9 , 'nombre' => 'Ahorro']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 10 , 'nombre' => 'Inversiones']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 11 , 'nombre' => 'Vehículos']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 12 , 'nombre' => 'Propiedades Hipotecarias']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 13 , 'nombre' => 'Distribución de la casa']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 14 , 'nombre' => 'Deudas']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 15 , 'nombre' => 'Gastos familiares']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 16 , 'nombre' => 'Situación Especial']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 17 , 'nombre' => 'Cursos cicles escolares']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 18 , 'nombre' => 'Salto de Hoja']
        );
        DB::table('catalogo_encuestas_preguntas_tipos')->insert(
            ['id' => 19 , 'nombre' => 'Espacio en blanco']
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
