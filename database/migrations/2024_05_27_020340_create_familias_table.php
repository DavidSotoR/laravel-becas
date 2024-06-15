<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ciclo_escolar');
            //Familia
            $table->string('nombre',240);
            //folio-< año - id del colegio -
            $table->string('situacion_beca',1200)->nullable();

            $table->string('nombre',240)->nullable();
            $table->string('nombre',240)->nullable();
            $table->timestamps();
            $table->foreign('id_ciclo_escolar')->references('id')->on('ciclo_escolar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('familias');
    }
}
