<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('receipts', function(Blueprint $table)
		{
			$table->increments('id')->comment('Primary key');
                        $table->integer('user_id')->unsigned()->comment('users table primary key');
                        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                        $table->integer('settled_amount');
                        $table->integer('total_amount');
                        $table->integer('type_id')->unsigned();
                        $table->integer('debited_to')->unsigned();
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
		Schema::drop('receipts');
	}

}
