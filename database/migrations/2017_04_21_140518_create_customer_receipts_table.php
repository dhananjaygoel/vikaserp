<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerReceiptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_receipts', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('customer_id')->unsigned();
//                        $table->integer('customer_id')->unsigned()->comment('customer table primary key');
//                        $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
                        $table->integer('settled_amount')->unsigned();
                        $table->integer('total_amount')->unsigned();
                        $table->integer('challan_id')->unsigned();
                        $table->integer('debited_by_type')->unsigned();
                        $table->integer('debited_to')->unsigned()->comment('debited_tos table primary key');
                        $table->foreign('debited_to')->references('id')->on('debited_tos')->onDelete('cascade');
                        $table->integer('receipt_id')->unsigned()->comment('receipts table primary key');
//                        $table->foreign('receipt_id')->references('id')->on('receipts')->onDelete('cascade');
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
		Schema::drop('customer_receipts');
	}

}
