<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerfilesCliente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfiles_cliente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_perfil')->constrained('perfiles')->onDelete('cascade');
            $table->foreignId('id_cliente')->constrained('clientes')->onDelete('cascade');
            $table->string('nombre');
            $table->string('activo')->default(false);
            $table->boolean('borrado')->default(false);
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
        Schema::dropIfExists('perfiles_cliente');
    }
}
