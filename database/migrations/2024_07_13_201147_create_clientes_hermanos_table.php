<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesHermanosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes_hermanos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',250);
            $table->timestamps();
        });


        Schema::table('clientes', function($table) {
            $table->unsignedBigInteger('id_clientes_hermanos')->nullable();
            $table->foreign('id_clientes_hermanos','id_clientes_hermanos_fkch')->references('id')->on('clientes_hermanos');
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
            $table->dropForeign('id_clientes_hermanos_fkch');
            $table->dropColumn('id_clientes_hermanos');
        });

        Schema::dropIfExists('clientes_hermanos');
    }
}
