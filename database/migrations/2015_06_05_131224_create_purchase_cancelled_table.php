<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseCancelledTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_cancelled', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('purchase_id');
			$table->integer('purchase_type');
                        $table->string('reason');                        
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
		Schema::drop('purchase_cancelled');
	}

}
