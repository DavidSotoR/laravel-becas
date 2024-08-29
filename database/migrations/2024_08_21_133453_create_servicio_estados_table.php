<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicioEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicio_estados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',120);
            $table->integer('estado');
        });

        DB::table('servicio_estados')->insert(
            ['id'=>1,'nombre' => 'Registrado','estado' => 0]
        );

        DB::table('servicio_estados')->insert(
            ['id'=>2,'nombre' => 'Asignado','estado' => 0]
        );

        DB::table('servicio_estados')->insert(
            ['id'=>3,'nombre' => 'Capturado','estado' => 0]
        );

        DB::table('servicio_estados')->insert(
            ['id'=>4,'nombre' => 'Revisión Calidad','estado' => 0]
        );

        DB::table('servicio_estados')->insert(
            ['id'=>5,'nombre' => 'Rechazada Calidad','estado' => 0]
        );

        DB::table('servicio_estados')->insert(
            ['id'=>6,'nombre' => 'Revisión Gerencia','estado' => 0]
        );

        DB::table('servicio_estados')->insert(
            ['id'=>7,'nombre' => 'Aprobado','estado' => 1]
        );

        DB::table('servicio_estados')->insert(
            ['id'=>8,'nombre' => 'Cerrado','estado' => 2]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicio_estados');
    }
}
