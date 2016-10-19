<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCustomers01 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customers', function(Blueprint $table) {
            		$table->string('mobile_status')->after('customer_status')->comment('showing Mobile registration status');
  			$table->string('otp')->default("0")->after('relationship_manager')->comment('otp for signing up');
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
            		$table->dropColumn('mobile_status');
			$table->dropColumn('otp');
       		});
	}

}
