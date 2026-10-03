<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CaracteresespecialUpdateServiciosEstudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicios_estudios', function (Blueprint $table) {
            // Asegurar que todas las columnas de texto usen utf8mb4
            $table->string('candidato', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->change();
            $table->string('situacion', 500)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->change();
            $table->string('email', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->change();
            $table->text('domicilio')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->change();
            $table->string('calle', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->change();
            $table->string('colonia', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->change();
            $table->string('municipio', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->change();
            $table->string('estado', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->change();
            $table->string('pais', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->nullable()->change();
            // Agrega más columnas si es necesario
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicios_estudios', function (Blueprint $table) {
            //
        });
    }
}

