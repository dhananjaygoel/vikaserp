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
			$table->integer('order_type');
                        $table->string('reason_complete');
                        $table->string('reason_cancel');
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
		Schema::drop('order_cancelled');
	}

}
