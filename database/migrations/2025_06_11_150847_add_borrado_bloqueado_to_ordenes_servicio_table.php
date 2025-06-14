<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBorradoBloqueadoToOrdenesServicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordenes_servicio', function (Blueprint $table) {
            $table->boolean('borrado')->default(false);
            $table->boolean('bloqueado')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordenes_servicio', function (Blueprint $table) {
            $table->dropColumn(['borrado', 'bloqueado']);
        });
    }
}
