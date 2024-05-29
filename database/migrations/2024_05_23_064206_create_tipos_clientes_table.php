<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiposClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',60);
            $table->timestamps();
        });

        DB::table('tipos_clientes')->insert(
            ['nombre' => 'Escuelas']
        );
        DB::table('tipos_clientes')->insert(
            ['nombre' => 'Empresas']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos_clientes');
    }
}
