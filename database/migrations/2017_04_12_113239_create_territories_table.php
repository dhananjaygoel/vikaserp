<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTerritoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('territories', function(Blueprint $table)
		{
			$table->increments('id')->comment('Primary key');
                        $table->string('teritory_name')->comment('Contains teritory name');                        
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
		Schema::drop('territories');
	}

}
