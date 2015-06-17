<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseChallanTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('purchase_challan', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_order_id');
            $table->integer('purchase_advice_id');
            $table->string('bill_number');
            $table->string('serial_number');
            $table->integer('supplier_id');
            $table->integer('created_by');
            $table->integer('delivery_location_id');
            $table->integer('order_for');
            $table->date('expected_delivery_date');
            $table->string('vat_percentage');
            $table->string('vehicle_number');
            $table->float('amount');
            $table->string('unloaded_by');
            $table->integer('unloading');
            $table->integer('labours');
            $table->float('discount');
            $table->integer('freight');
            $table->integer('unloading_charge');
            $table->float('grand_total');
            $table->enum('order_status', array('pending', 'completed'));
            $table->string('remarks');
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
        Schema::drop('purchase_challan');
    }

}
