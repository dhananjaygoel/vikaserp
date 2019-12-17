<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('orders', function($table) {
            $table->integer('del_supervisor')->nullable();
            $table->integer('del_boy')->nullable();
            $table->string('empty_truck_weight',15)->nullable();
            $table->string('final_truck_weight',15)->nullable();
            $table->string('party_name',50)->nullable();
            $table->text('product_detail_table')->nullable();
            $table->string('labour_pipe',30)->nullable();
            $table->string('labour_structure',30)->nullable();
            $table->string('vehicle_number',30)->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */


	public function down()
	{
		//
	}

}
