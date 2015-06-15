<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseAdviceTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('purchase_advice', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_id');
            $table->integer('created_by');
            $table->date('purchase_advice_date');
            $table->string('serial_number');
            $table->integer('delivery_location_id');
            $table->integer('order_for');
            $table->string('vat_percentage');
            $table->string('expected_delivery_date');
            $table->integer('total_price');
            $table->text('remarks');
            $table->integer('advice_status');
            $table->string('vehicle_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('purchase_advice');
    }

}
