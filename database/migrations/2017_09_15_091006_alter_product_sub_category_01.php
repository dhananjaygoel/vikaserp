<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductSubCategory01 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::table('product_sub_category', function(Blueprint $table) {
                $table->string('hsn_code')->after('size')->comment('HSN code')->index('hsn_code'); 
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::table('product_sub_category', function(Blueprint $table) {
                $table->dropColumn('hsn_code'); 
            });
	}

}
