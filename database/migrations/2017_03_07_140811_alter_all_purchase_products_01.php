<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAllPurchaseProducts01 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('all_purchase_products', function(Blueprint $table)
		{
			DB::statement(DB::raw("ALTER TABLE all_purchase_products CHANGE COLUMN price price decimal(12,2) NOT NULL;"));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('all_purchase_products', function(Blueprint $table)
		{
			DB::statement(DB::raw("ALTER TABLE all_purchase_products CHANGE COLUMN price price decimal(8,2) NOT NULL;"));
		});
	}

}
