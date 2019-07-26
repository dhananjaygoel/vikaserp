<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTruckdelbysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('truckdelbys', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('del_supervisor');
                        $table->integer('del_boy');
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
		Schema::drop('truckdelbys');
	}

}
