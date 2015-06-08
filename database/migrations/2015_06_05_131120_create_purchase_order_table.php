<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_order', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('supplier_id');
			$table->integer('created_by');
                        $table->integer('is_view_all');
                        $table->integer('delivery_location_id');
                        $table->string('order_for');
                        $table->integer('is_vat');
                        $table->float('vat_percentage');
                        $table->integer('total_price');
                        $table->string('estimate_delivery_date');
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
		Schema::drop('purchase_order');
	}

}
