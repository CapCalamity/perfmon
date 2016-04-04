<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disk', function (Blueprint $table) {
            $table->bigInteger('id', true, true);
            $table->unsignedBigInteger('record_id');

            $table->string('opts');
            $table->string('fstype');
            $table->string('device');
            $table->string('mountpoint');
            $table->bigInteger('free');
            $table->bigInteger('total');
            $table->bigInteger('used');
            $table->double('used_percent');

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
        Schema::dropIfExists('disk');
    }
}
