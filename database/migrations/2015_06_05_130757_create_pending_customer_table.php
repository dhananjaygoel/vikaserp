<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendingCustomerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pending_customer', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('customer_name',100);
                        $table->string('contact_person',100);
                        $table->string('phone_number',20); 
                        $table->integer('delivery_location_id');
                        $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pending_customer');
	}

}
