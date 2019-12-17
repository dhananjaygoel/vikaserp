<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAllOrderProductsIndex05 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('all_order_products', function(Blueprint $table)
		{
                    $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());
                    if (!$schema_builder->hasIndex('from'))
			DB::statement(DB::raw("ALTER TABLE  `all_order_products` ADD INDEX (`from`) ;"));
                     if (!$schema_builder->hasIndex('parent'))
			DB::statement(DB::raw("ALTER TABLE  `all_order_products` ADD INDEX (`parent`) ;"));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('all_order_products', function(Blueprint $table)
		{
                    $schema_builder = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableDetails($table->getTable());
                    if ($schema_builder->hasIndex('from'))
			DB::statement(DB::raw("ALTER TABLE  `all_order_products` DROP INDEX  `from` ;"));
                    if ($schema_builder->hasIndex('parent'))
			DB::statement(DB::raw("ALTER TABLE  `all_order_products` DROP INDEX  `parent` ;"));
		});
	}

}
