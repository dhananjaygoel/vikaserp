<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderCancelledTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_cancelled', function(Blueprint $table)
		{
			
			$table->increments('id');
                        $table->integer('order_id');
			$table->enum('order_type', array('order', 'delivery_order', 'delivery_challan'))->comment('order type');			
                        $table->string('reason_type');
                        $table->text('reason');
                        $table->integer('cancelled_by')->comment('order cancelled by which user');
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
		Schema::drop('order_cancelled');
	}

}
