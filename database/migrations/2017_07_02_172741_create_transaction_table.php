<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_transaction', function (Blueprint $table) {
            $table->increments('id_transaction');
            $table->unsignedInteger('id_activity');
            $table->unsignedInteger('id_activity_date');
            $table->unsignedInteger('id_user');
            $table->integer('quantity');
            $table->integer('total_price');
            $table->tinyInteger('status')->comment('0: Belum bayar, 1: Menunggu Konfirmasi, 2: Sudah bayar, 3: Selesai, -1: Batal');
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
        Schema::dropIfExists('tb_transaction');
    }
}
