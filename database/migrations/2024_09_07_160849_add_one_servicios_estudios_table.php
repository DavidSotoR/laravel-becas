<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOneServiciosEstudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicios_estudios', function (Blueprint $table) {
            $table->unsignedBigInteger('id_familia')->nullable();
            $table->foreign('id_familia')->references('id')->on('users')->onDelete('cascade');
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
            $table->dropForeign(['id_familia']);
            $table->dropColumn('id_familia');
        });
    }
}
