<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveForeignKeyFromFamiliasDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('familias_documentos', function (Blueprint $table) {
            // Eliminar la clave foránea en 'id_familia'
            $table->dropForeign(['id_familia']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('familias_documentos', function (Blueprint $table) {
            // Restaurar la clave foránea en 'id_familia'
            $table->foreign('id_familia')->references('id')->on('familias');
        });
    }
}
