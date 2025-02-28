<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddFourServiciosEstudiosRespuestas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicios_estudios_respuestas', function (Blueprint $table) {
            if (!Schema::hasColumn('servicios_estudios_respuestas', 'id_respuestas_clasificacions')) {
                $table->unsignedBigInteger('id_respuestas_clasificacions')->nullable();
            }
        });
        $foreignKeys = DB::select("SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'servicios_estudios_respuestas' 
            AND COLUMN_NAME = 'id_respuestas_clasificacions'
            AND CONSTRAINT_SCHEMA = DATABASE()");

        if (empty($foreignKeys)) {
            Schema::table('servicios_estudios_respuestas', function (Blueprint $table) {
                $table->foreign('id_respuestas_clasificacions', 'id_respuestas_clasificacions_forean')
                    ->references('id')
                    ->on('servicios_estudios_respuestas_clasificacions');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicios_estudios_respuestas', function (Blueprint $table) {
            if (Schema::hasColumn('servicios_estudios_respuestas', 'id_respuestas_clasificacions')) {
                $table->dropForeign(['id_respuestas_clasificacions']);
                $table->dropColumn('id_respuestas_clasificacions');
            }
        });
    }
}
