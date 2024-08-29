<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnePerfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perfiles', function (Blueprint $table) {
            $table->boolean('interno')->default(false);
        });

        DB::table('perfiles')
        ->whereIn('nombre', ['Administrador','Gerencia','Calidad','Colaboradores'])
        ->update(['interno' => true]);
        DB::table('perfiles')
        ->whereIn('nombre', ['Empresas','Familias'])
        ->update(['interno' => false]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perfiles', function (Blueprint $table) {
            $table->dropColumn('interno');
        });
    }
}
