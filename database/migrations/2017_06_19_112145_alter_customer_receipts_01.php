<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCustomerReceipts01 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customer_receipts', function(Blueprint $table)
		{
                    $table->string('narration')->after('receipt_id');
                    $table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customer_receipts', function(Blueprint $table)
		{
                    $table->dropColumn('narration');
                    $table->dropColumn('deleted_at');
		});
	}

}
