<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnMaxParticipants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_activity', function (Blueprint $table) {
            $table->dropColumn('max_participants');
        });

        Schema::table('tb_activity_date', function (Blueprint $table) {
            $table->integer('max_participants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_activity_date', function (Blueprint $table) {
            $table->dropColumn('max_participants');
        });

        Schema::table('tb_activity', function (Blueprint $table) {
            $table->integer('max_participants');
        });
    }
}
