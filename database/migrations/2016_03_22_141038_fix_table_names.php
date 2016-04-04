<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixTableNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('memory', 'memories');
        Schema::rename('system_user', 'system_users');
        Schema::rename('net_io', 'net_ios');
        Schema::rename('disk', 'disks');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('memories', 'memory');
        Schema::rename('system_users', 'system_user');
        Schema::rename('net_ios', 'net_io');
        Schema::rename('disks', 'disk');
    }
}
