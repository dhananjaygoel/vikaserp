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
                        $table->integer('order_id');
                        $table->integer('delivery_order_id');
                        $table->integer('customer_id');
			$table->integer('created_by');                        
                        $table->string('bill_number');
                        $table->string('loaded_by');
                        $table->integer('labours');
                        $table->string('discount',20);
                        $table->integer('freight');
                        $table->integer('loading_charge');
                        $table->string('vat_percentage',20);
                        $table->string('total_price',20);
                        $table->enum('challan_status',array('pending','completed'));
                        $table->softDeletes();
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
