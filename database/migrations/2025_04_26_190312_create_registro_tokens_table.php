<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistroTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registro_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_proyecto');
            $table->unsignedBigInteger('id_orden_servicio');
            $table->text('token');          // token completo
            $table->text('token_parte1'); // primera parte visible en el link
            $table->text('token_parte2'); // segunda parte oculta
            $table->boolean('activo')->default(true);
            $table->text('link_registro')->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registro_tokens');
    }
}
