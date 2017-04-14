<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('collection_user_location', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('user_id')->comment("Delivery Location Id");
            $table->string('location_id')->comment("Delivery Location Id");
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
		Schema::drop('collection_user_location');
	}

}
