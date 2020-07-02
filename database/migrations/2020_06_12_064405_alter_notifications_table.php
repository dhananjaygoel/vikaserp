<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('user_read_status',array('0','1'))->after('assigned_to');
			$table->enum('admin_read_status',array('0','1'))->after('user_read_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('user_read_status');
			$table->dropColumn('admin_read_status');
        });
        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('status',array('0','1'))->after('assigned_to');
        });
    }
}
