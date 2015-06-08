<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryChallanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_challan', function(Blueprint $table)
		{
                        $table->increments('id');
                        $table->integer('customer_id');
			$table->integer('created_by');
                        $table->string('serial_number');
                        $table->string('bill_number');
                        $table->integer('delivery_location_id');
                        $table->integer('is_vat');
                        $table->float('vat_percentage');
                        $table->integer('estimate_price');
                        $table->string('estimate_delivery_date');
                        $table->string('target_delivery_date');
                        $table->string('remarks'); 
                        $table->string('vehicle_number');
                        $table->string('loaded_by');
                        $table->integer('labours');
                        $table->float('discount');
                        $table->integer('freight');
                        $table->integer('loading_charge');
                        $table->float('amount');
                        $table->float('grand_total');
                        $table->string('driver_name')   ;
                        $table->string('driver_contact_number',20);
                        $table->integer('order_status');
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
		Schema::drop('delivery_challan');
	}

}
