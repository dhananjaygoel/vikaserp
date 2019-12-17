<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCustomers05 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::table('customers', function(Blueprint $table) {
                $table->string('gstin_number')->after('city')->comment('GSTIN number')->index('gstin_number'); 
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::table('customers', function(Blueprint $table) {
                $table->dropColumn('gstin_number'); 
            });
	}

}
