<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoadTruck extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('load_truck', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('role_id');
                        $table->integer('deliver_id');
                        $table->integer('empty_truck_weight');
                        $table->integer('final_truck_weight');
                        $table->integer('userid');
			
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
		Schema::drop('load_truck');
	}

}
