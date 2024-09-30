<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFiveServiciosEstudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicios_estudios', function (Blueprint $table) {
            $table->date('visita_fecha')->nullable();
            $table->time('visita_hora')->nullable();
            $table->string('visita_recordatorio',1200)->nullable();
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
            $table->dropColumn('visita_fecha');
            $table->dropColumn('visita_hora');
            $table->dropColumn('visita_recordatorio');
        });
    }
}
