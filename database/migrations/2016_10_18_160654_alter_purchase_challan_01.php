<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPurchaseChallan01 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE  `purchase_challan` CHANGE  `grand_total`  `grand_total` VARCHAR( 20 ) NOT NULL ;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE  `purchase_challan` CHANGE  `grand_total`  `grand_total` DECIMAL( 8, 2 ) NOT NULL ;');
	}

}
