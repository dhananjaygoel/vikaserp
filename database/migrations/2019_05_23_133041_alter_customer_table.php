<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCustomerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customers', function($table) {
            $table->integer('quickbook_a_customer_id')->nullable();
            $table->integer('quickbook_a_supplier_id')->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customers', function($table) {
                $table->dropColumn('quickbook_a_customer_id');
                $table->dropColumn('quickbook_a_supplier_id');
            });
	}

}
