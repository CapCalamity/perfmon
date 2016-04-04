<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNetioPerSecondValues
    extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('net_ios', function (Blueprint $table) {
            $table->bigInteger('bytes_recv_sec');
            $table->bigInteger('bytes_sent_sec');
            $table->bigInteger('packets_recv_sec');
            $table->bigInteger('packets_sent_sec');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('net_ios', function (Blueprint $table) {
            $table->dropColumn('bytes_recv_sec');
            $table->dropColumn('bytes_sent_sec');
            $table->dropColumn('packets_recv_sec');
            $table->dropColumn('packets_sent_sec');
        });
    }
}
