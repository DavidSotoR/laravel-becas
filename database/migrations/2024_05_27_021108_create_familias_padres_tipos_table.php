<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliasPadresTiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familias_padres_tipos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo',60);
            $table->timestamps();
        });

        DB::table('familias_padres_tipos')->insert(
            ['tipo' => 'Padre']
        );
        DB::table('familias_padres_tipos')->insert(
            ['tipo' => 'Madre']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('familias_padres_tipos');
    }
}
