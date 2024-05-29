<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliasDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familias_documentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_familia');
            $table->unsignedBigInteger('id_familias_documentos_tipo');
            $table->string('nombre',240)->nullable();
            $table->string('directorio',240)->nullable();
            $table->string('alias',240)->nullable();
            $table->timestamps();
            $table->foreign('id_familia')->references('id')->on('familias');
            $table->foreign('id_familias_documentos_tipo')->references('id')->on('familias_documentos_tipos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('familias_documentos');
    }
}
