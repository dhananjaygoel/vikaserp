<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_products', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('purchase_order_id');
			$table->integer('purchase_order_type');
                        $table->integer('product_sub_category_id');
                        $table->integer('unit_id');
                        $table->integer('quantity');
                        $table->float('price');
                        $table->integer('present_shipping');
                        $table->string('remarks'); 
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
		Schema::drop('purchase_products');
	}

}
