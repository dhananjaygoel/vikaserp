<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDeliveryOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('delivery_order', function($table) {
			$table->integer('is_editable')->default(0);
            $table->integer('del_supervisor')->nullable();
            $table->integer('del_boy')->nullable();
            $table->string('party_name',50)->nullable();
            $table->text('product_detail_table')->nullable();
            $table->string('labour_pipe',30)->nullable();
            $table->string('labour_structure',30)->nullable();
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
