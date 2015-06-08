<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSubCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_sub_category', function(Blueprint $table)
		{
                        $table->increments('id');
			$table->integer('product_category_id');
                        $table->integer('size');
                        $table->integer('weight');
                        $table->integer('thickness');                        
			$table->integer('difference');
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
		Schema::drop('product_sub_category');
	}

}
