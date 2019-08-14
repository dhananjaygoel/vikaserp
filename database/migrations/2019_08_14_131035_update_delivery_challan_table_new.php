<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDeliveryChallanTableNew extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('delivery_challan', function($table) {
            $table->dateTime('deleted_at')->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('delivery_challan',function($table){
          $table->dropColumn('deleted_at');
        });
	}

}
