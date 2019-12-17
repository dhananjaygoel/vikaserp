<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLabours01 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('labours', function(Blueprint $table)
		{
                    $table->renameColumn('labour_name', 'first_name');
                    $table->renameColumn('location', 'password');
                    $table->string('last_name')->after('labour_name');	
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('labours', function(Blueprint $table)
		{
                    $table->dropColumn('last_name');
                    $table->renameColumn('frist_name','labour_name');
                    $table->renameColumn('password','location');
		});
	}

}
