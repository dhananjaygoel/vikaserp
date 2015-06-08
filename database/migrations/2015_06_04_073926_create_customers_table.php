<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('owner_name', 100);
                        $table->string('company_name',100);
                        
                        $table->string('address1');
                        $table->string('address2');
                        $table->string('city',20);
                        $table->string('state',100);
                        $table->string('zip',10);                        
                        $table->string('email');
                        $table->string('phone_number1',20);
                        $table->string('phone_number2',20);
                        $table->string('username',100);
                        $table->string('password', 60);
			//$table->rememberToken();
			
			$table->string('tally_name',100);
                        $table->string('tally_category',100);
                        $table->string('tally_sub_category',100); 
                        $table->string('vat_tin_number',100);
                        $table->string('excise_number',100);
                        $table->integer('default_delivery_location'); 
                        $table->string('credit_period',50); 
                        $table->integer('relationship_manager'); 
                        $table->integer('is_set_price'); 
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
		Schema::drop('customers');
	}

}
