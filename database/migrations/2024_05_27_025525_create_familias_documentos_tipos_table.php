<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliasDocumentosTiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familias_documentos_tipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',60);
            $table->string('tipo',60);
            $table->string('descripcion',1200)->nullable();
            $table->timestamps();
        });

        DB::table('familias_documentos_tipos')->insert(
            [
                'nombre' => 'INGRESOS',
                'tipo' => '.pdf',
                'descripcion' => '']
        );
        DB::table('familias_documentos_tipos')->insert(
            [
                'nombre' => 'DESEMPLEO',
                'tipo' => '.pdf',
                'descripcion' => ""]
        );
        DB::table('familias_documentos_tipos')->insert(
            [
                'nombre' => 'CASA HABITACION',
                'tipo' => '.pdf',
                'descripcion' => ""]
        );
        DB::table('familias_documentos_tipos')->insert(
            [
                'nombre' => 'AUTOMOVILES',
                'tipo' => '.pdf',
                'descripcion' => ""]
        );
        DB::table('familias_documentos_tipos')->insert(
            [
                'nombre' => 'COMPROBANTES',
                'tipo' => '.pdf',
                'descripcion' => ""]
        );
        DB::table('familias_documentos_tipos')->insert(
            [
                'nombre' => 'ANEXOS',
                'tipo' => '.pdf',
                'descripcion' => ""]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('familias_documentos_tipos');
    }
}
