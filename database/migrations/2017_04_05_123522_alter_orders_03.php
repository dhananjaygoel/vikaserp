<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrders03 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement(DB::raw("ALTER TABLE  `orders` ADD  `is_approved` VARCHAR( 5 ) NOT NULL DEFAULT  'no' AFTER  `order_status` ,
ADD INDEX (  `is_approved` ) ;"));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		DB::statement(DB::raw("ALTER TABLE  `orders` DROP  `is_approved` ;"));
	}

}
