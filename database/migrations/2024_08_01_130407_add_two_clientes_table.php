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
            $table->boolean('requiere_facturar')->default(false);
            $table->string('rfc',120)->nullable();
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
            if (Schema::hasColumn('clientes', 'requiere_facturar')) {
                $table->dropColumn('requiere_facturar');
            }
            if (Schema::hasColumn('clientes', 'rfc')) {
                $table->dropColumn('rfc');
            }
            if (Schema::hasColumn('clientes', 'rso')) {
                $table->dropColumn('rso');
            }
            if (Schema::hasColumn('clientes', 'nombre_uno')) {
                $table->dropColumn('nombre_uno');
            }
            if (Schema::hasColumn('clientes', 'telefono_uno')) {
                $table->dropColumn('telefono_uno');
            }
            if (Schema::hasColumn('clientes', 'nombre_dos')) {
                $table->dropColumn('nombre_dos');
            }
            if (Schema::hasColumn('clientes', 'telefono_dos')) {
                $table->dropColumn('telefono_dos');
            }
            if (Schema::hasColumn('clientes', 'telefono_mobil')) {
                $table->dropColumn('telefono_mobil');
            }
            if (Schema::hasColumn('clientes', 'calle')) {
                $table->dropColumn('calle');
            }
            if (Schema::hasColumn('clientes', 'entre_cale')) {
                $table->dropColumn('entre_cale');
            }
            if (Schema::hasColumn('clientes', 'colonia')) {
                $table->dropColumn('colonia');
            }
            if (Schema::hasColumn('clientes', 'codigo_postal')) {
                $table->dropColumn('codigo_postal');
            }
            if (Schema::hasColumn('clientes', 'ciudad')) {
                $table->dropColumn('ciudad');
            }
            if (Schema::hasColumn('clientes', 'estado')) {
                $table->dropColumn('estado');
            }
            if (Schema::hasColumn('clientes', 'pais')) {
                $table->dropColumn('pais');
            }
            if (Schema::hasColumn('clientes', 'rason_social')) {
                $table->dropColumn('rason_social');
            }
        });
    }
}
