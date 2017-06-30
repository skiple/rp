<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_user', function (Blueprint $table) {
            $table->increments('id_user');
            $table->string('email', 64)->unique();
            $table->string('password', 255);
            $table->string('first_name', 32);
            $table->string('last_name', 32);
            $table->string('phone', 16);
            $table->date('birthdate');
            $table->integer('isAdmin')->default(0);
            $table->timestamps();
            $table->string('remember_token', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('tb_user');
    }
}
