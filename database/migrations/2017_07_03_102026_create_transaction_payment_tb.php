<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionPaymentTb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_transaction_payment', function (Blueprint $table) {
            $table->increments('id_transaction_payment');
            $table->unsignedInteger('id_transaction')->unique();
            $table->string('email', 64);
            $table->string('name', 32);
            $table->string('phone', 16);
            $table->integer('amount');
            $table->string('bank', 16);
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
        Schema::dropIfExists('tb_transaction_payment');
    }
}
