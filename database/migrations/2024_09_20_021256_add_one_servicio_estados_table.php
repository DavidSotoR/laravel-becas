<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOneServicioEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicio_estados', function (Blueprint $table) {
            $table->string('color',120)->default('');
        });

        DB::table('servicio_estados')->where('id', 1)->update(
            ['color' => '#E8E8E8']
        );

        DB::table('servicio_estados')->where('id', 2)->update(
            ['color' => '#FE9900']
        );

        DB::table('servicio_estados')->where('id', 3)->update(
            ['color' => '#FFDE59']
        );

        DB::table('servicio_estados')->where('id', 4)->update(
            ['color' => '#98F5F9']
        );

        DB::table('servicio_estados')->where('id', 5)->update(
            ['color' => '#CC6CE7']
        );

        DB::table('servicio_estados')->where('id', 6)->update(
            ['color' => '#060270']
        );

        DB::table('servicio_estados')->where('id', 7)->update(
            ['color' => '#7DDA58']
        );

        DB::table('servicio_estados')->where('id', 8,)->update(
            ['color' => '#E4080A']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicio_estados', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
}
