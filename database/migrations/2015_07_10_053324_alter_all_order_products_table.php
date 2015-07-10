<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAllOrderProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::table('all_order_products', function(Blueprint $table)
                {
                    $table->string('from')->comment('order from which module');
                });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            
                Schema::table('all_order_products',function($table){
                    $table->dropColumn('from');
                });
	}

}
