<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('customer_id');
			$table->integer('created_by');
                        $table->integer('delivery_location_id');
                        $table->integer('is_vat');
                        $table->float('vat_percentage');
                        $table->integer('estimate_price');
                        $table->string('estimate_delivery_date');
                        $table->string('target_delivery_date');
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
		Schema::drop('order');
	}

}
