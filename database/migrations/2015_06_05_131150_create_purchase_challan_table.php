<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseChallanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_challan', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('supplier_id');
			$table->integer('created_by');
                        $table->string('purchase_challan_date');                        
                        $table->string('serial_number');
                        $table->string('bill_number');
                        $table->integer('delivery_location_id');
                        $table->integer('order_for');
                        $table->integer('is_vat');
                        $table->float('vat_percentage');
                        
                        $table->string('estimate_delivery_date');
                        $table->string('target_delivery_date');
                        
                        $table->string('loaded_by');
                        $table->integer('labours');
                        $table->float('discount');
                        $table->integer('freight');
                        $table->integer('loading_charge');
                        $table->float('amount');
                        $table->float('grand_total');
                        $table->string('vehicle_name');
                        $table->string('remarks'); 
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
		Schema::drop('purchase_challan');
	}

}
