<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePurchaseOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('purchase_order', function (Blueprint $table) {
			$table->string( 'vat_status', 50 )->comments('A=include_vat,P=exclude_vat')->nullable();
			$table->integer('is_editable')->default(0);
                               $table->string( 'length', 50 )->comments('Length field for table')->nullable();
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
