<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memory', function (Blueprint $table) {
            $table->bigInteger('id', true, true);
            $table->unsignedBigInteger('record_id');

            $table->double('swap_percent');
            $table->bigInteger('swap_used');
            $table->bigInteger('swap_in');
            $table->bigInteger('swap_out');
            $table->bigInteger('swap_free');
            $table->bigInteger('swap_total');

            $table->double('virt_percent');
            $table->bigInteger('virt_buffers');
            $table->bigInteger('virt_inactive');
            $table->bigInteger('virt_used');
            $table->bigInteger('virt_free');
            $table->bigInteger('virt_active');
            $table->bigInteger('virt_cached');
            $table->bigInteger('virt_available');
            $table->bigInteger('virt_total');

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
        Schema::dropIfExists('memory');
    }
}
