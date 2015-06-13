<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllOrderProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('all_order_products', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('order_id')->comment('order id for product list');
                        $table->enum('order_type', array('order', 'delivery_order', 'delivery_challan'))->comment('order type');			
                        $table->integer('product_category_id')->comment('Product category');
                        $table->integer('unit_id')->comment('Unit for product');
                        $table->integer('quantity')->comment('Quantity for product');
                        $table->integer('actual_pieces')->comment('Actual pieces of product');
                        $table->integer('price')->comment('Price for product');
                        $table->integer('present_shipping')->comment('Present shipping');
                        $table->text('remarks')->comment('Individual Remarks for product');
                        $table->timestamps();
                        $table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('all_order_products');
	}

}
