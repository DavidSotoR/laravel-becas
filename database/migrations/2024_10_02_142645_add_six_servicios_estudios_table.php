<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSixServiciosEstudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicios_estudios', function (Blueprint $table) {
            $table->string('calle',120)->nullable();
            $table->string('numero_exterior',10)->nullable();
            $table->string('colonia',60)->nullable();
            $table->string('municipio',60)->nullable();
            $table->string('estado',60)->nullable();
            $table->string('codigo_postal',5)->nullable();
            $table->string('pais',60)->nullable();
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
            $table->dropColumn('calle');
            $table->dropColumn('numero_exterior');
            $table->dropColumn('colonia');
            $table->dropColumn('municipio');
            $table->dropColumn('estado');
            $table->dropColumn('codigo_postal');
            $table->dropColumn('pais');
        });
    }
}
