<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_perfil');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('activo')->default(false);
            $table->boolean('externo')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('id_perfil')->references('id')->on('perfiles');
        });

        DB::table('users')->insert(
            [
                'name' => 'David Soto'
                ,'email' => 'davidsotord93@gmail.com'
                ,'id_perfil' => 1
                ,'password' => bcrypt('Admin123')
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
