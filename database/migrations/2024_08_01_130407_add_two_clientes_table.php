<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('rso',120)->nullable();
            $table->string('nombre_uno',120)->nullable();
            $table->string('telefono_uno',120)->nullable();
            $table->string('nombre_dos',120)->nullable();
            $table->string('telefono_dos',120)->nullable();
            $table->string('telefono_mobil',120)->nullable();
            $table->string('calle',120)->nullable();
            $table->string('entre_cale',120)->nullable();
            $table->string('colonia',120)->nullable();
            $table->string('codigo_postal',5)->nullable();
            $table->string('ciudad', 120)->nullable();
            $table->string('estado', 120)->nullable();
            $table->string('pais', 120)->nullable();
            $table->string('rason_social', 120)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {

            $table->dropColumn('rso');
            $table->dropColumn('nombre_uno');
            $table->dropColumn('telefono_uno');
            $table->dropColumn('nombre_dos');
            $table->dropColumn('telefono_dos');
            $table->dropColumn('telefono_mobil');
            $table->dropColumn('calle');
            $table->dropColumn('entre_cale');
            $table->dropColumn('colonia');
            $table->dropColumn('codigo_postal');
            $table->dropColumn('ciudad');
            $table->dropColumn('estado');
            $table->dropColumn('pais');
            $table->dropColumn('rason_social');
        });
    }
}
