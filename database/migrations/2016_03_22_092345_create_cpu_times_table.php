<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpuTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpu_times', function (Blueprint $table) {
            $table->bigInteger('id', true, true);
            $table->unsignedBigInteger('record_id');

            $table->double('user');
            $table->double('user_percent');
            $table->double('steal');
            $table->double('steal_percent');
            $table->double('system');
            $table->double('system_percent');
            $table->double('irq');
            $table->double('irq_percent');
            $table->double('softirq');
            $table->double('softirq_percent');
            $table->double('nice');
            $table->double('nice_percent');
            $table->double('guest_nice');
            $table->double('guest_nice_percent');
            $table->double('guest');
            $table->double('guest_percent');
            $table->double('idle');
            $table->double('idle_percent');
            $table->double('iowait');
            $table->double('iowait_percent');

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
        Schema::dropIfExists('cpu_times');
    }
}
