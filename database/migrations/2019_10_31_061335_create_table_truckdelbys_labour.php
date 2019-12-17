<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTruckdelbysLabour extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('truckdelbys_labour', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('del_boy_id');
            $table->integer('labour_id');
            $table->integer('delivery_id');
                      
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
		Schema::drop('truckdelbys_labour');
	}

}
