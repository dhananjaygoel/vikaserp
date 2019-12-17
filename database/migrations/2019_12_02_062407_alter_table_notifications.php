<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNotifications extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('notifications', function (Blueprint $table) {

            $table->integer('order_id')->after('id');
			$table->string('order_type')->after('order_id');
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
			$table->dropColumn('order_id');
            $table->dropColumn('order_type');
		});
	}

}
