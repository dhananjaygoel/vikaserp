<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerReceiptsDebitedTosTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('customer_receipts_debited_tos', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->string('settled_amount');
            $table->integer('debited_by_type')->unsigned();
            $table->integer('receipt_id')->unsigned()->comment('receipts table primary key');
            $table->string('narration')->comment('narration');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('customer_receipts_debited_tos');
    }

}
