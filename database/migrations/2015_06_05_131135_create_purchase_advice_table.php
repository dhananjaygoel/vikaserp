<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseAdviceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_advice', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('supplier_id');
			$table->integer('created_by');
                        $table->string('purchase_advice_date');                        
                        $table->string('serial_number');
                        $table->integer('delivery_location_id');
                        $table->integer('order_for');
                        $table->integer('is_vat');
                        $table->float('vat_percentage');
                        $table->integer('estimate_price');
                        $table->string('estimate_delivery_date');
                        $table->string('target_delivery_date');
                        $table->string('remarks'); 
                        $table->integer('order_status');
                        $table->string('vehicle_number');
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
		Schema::drop('purchase_advice');
	}

}
