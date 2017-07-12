<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_log_api', function (Blueprint $table) {
            $table->increments('id_log');
            $table->integer('code');
            $table->tinyInteger('status');
            $table->string('message');
            $table->dateTime('time');
            $table->string('url');
            $table->text('method');
            $table->text('action');
            $table->text('parameter')->nullable();
            $table->text('result')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_log_api');
    }
}
