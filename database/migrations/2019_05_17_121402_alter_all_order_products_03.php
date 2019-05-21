<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAllOrderProducts03 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('all_order_products', function(Blueprint $table) {
			$table->double('length')->after('unit_id')->default('0')->comment('length');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('all_order_products', function(Blueprint $table) {
			$table->dropColumn('length');
		});
	}

}
