<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEightUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('calle')->nullable();
            $table->string('numero_exterior')->nullable();
            $table->string('colonia')->nullable();
            $table->string('municipio')->nullable();
            $table->string('estado')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('pais')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
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
