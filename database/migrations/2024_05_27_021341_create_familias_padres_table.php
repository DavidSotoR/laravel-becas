<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliasPadresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familias_padres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_familias_padres_tipo');
            $table->string('nombre',120);
            $table->integer('edad');
            $table->boolean('vive')->default(true);
            $table->string('direccion',240);
            $table->string('ocupacion_actual',240)->nullable();
            $table->string('empresa_trabajo',240)->nullable();
            $table->string('email',60)->nullable();
            $table->string('telefono_casa',60)->nullable();
            $table->timestamps();
            $table->foreign('id_familias_padres_tipo')->references('id')->on('familias_padres_tipos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('familias_padres');
    }
}
