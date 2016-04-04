<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetIoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('net_io', function (Blueprint $table) {
            $table->bigInteger('id', true, true);
            $table->unsignedBigInteger('record_id');

            $table->string('interface');
            $table->bigInteger('errin');
            $table->bigInteger('errout');
            $table->bigInteger('bytes_recv');
            $table->bigInteger('bytes_sent');
            $table->bigInteger('dropout');
            $table->bigInteger('dropin');
            $table->bigInteger('packets_recv');
            $table->bigInteger('packets_sent');

            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('net_io');
    }
}
