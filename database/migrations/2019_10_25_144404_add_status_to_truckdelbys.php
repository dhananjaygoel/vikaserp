<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToTruckdelbys extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		Schema::table('truckdelbys', function (Blueprint $table) {

            $table->integer('assigned_status')->default('1')->after('delivery_id');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('truckdelbys', function (Blueprint $table) {

            $table->dropColumn('assigned_status');
		});
	}

}
