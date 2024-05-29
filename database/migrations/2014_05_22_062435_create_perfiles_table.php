<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfiles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',60);
            $table->boolean('activo')->default(true);
            $table->string('descripcion',260)->nullable();
            $table->timestamps();
        });

        DB::table('perfiles')->insert(
            ['nombre' => 'Administrador','activo' => true,'descripcion'=>'Administración del sistema.']
        );
        DB::table('perfiles')->insert(
            ['nombre' => 'Gerencia','activo' => true,'descripcion'=>'Todos los permisos de administración, a excepción de editar usuarios.']
        );
        DB::table('perfiles')->insert(
            ['nombre' => 'Calidad','activo' => true,'descripcion'=>'Validación de la redacción después de la aplicación de la encuesta.']
        );
        DB::table('perfiles')->insert(
            ['nombre' => 'Colaboradores','activo' => true,'descripcion'=>'Permisos para aplicar encuestas.']
        );
        DB::table('perfiles')->insert(
            ['nombre' => 'Empresas','activo' => true,'descripcion'=>'Acceso para colegios y empresas.']
        );
        DB::table('perfiles')->insert(
            ['nombre' => 'Familias','activo' => true,'descripcion'=>'Acceso para subir documentación.']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perfiles');
    }
}
