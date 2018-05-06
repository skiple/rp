<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_payment_methods', function (Blueprint $table) {
            $table->increments('id_payment_method');
            $table->string('payment_method_name', 32);
            $table->string('payment_method_photo', 64)->nullable();
            $table->text('description')->nullable();
            $table->string('account_number', 32);
            $table->string('account_name', 64);
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
        Schema::dropIfExists('tb_payment_methods');
    }
}
