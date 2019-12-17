<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaboursTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('labours', function(Blueprint $table)
		{
			$table->increments('id')->comment('Primary key');
                        $table->string('labour_name')->comment('Contains labour name');
                        $table->string('phone_number', 20);
                        $table->string('location')->comment('Contains location');
                        $table->index(['labour_name']);
                        $table->index(['phone_number']);
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
		Schema::drop('labours');
	}

}
