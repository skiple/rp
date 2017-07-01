<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_activity', function (Blueprint $table) {
            $table->increments('id_activity');
            $table->string('activity_name', 128);
            $table->string('host_name', 64);
            $table->longText('host_profile');
            $table->tinyInteger('duration');
            $table->longText('description');
            $table->integer('max_participants');
            $table->string('photo1', 64);
            $table->string('photo2', 64);
            $table->string('photo3', 64);
            $table->string('photo4', 64);
            $table->integer('price');
            $table->longText('provide');
            $table->string('location', 100);
            $table->longText('itinerary');
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
        Schema::dropIfExists('tb_activity');
    }
}
