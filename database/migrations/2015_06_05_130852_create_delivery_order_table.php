<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryOrderTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('delivery_order', function(Blueprint $table) {

            $table->increments('id');
            $table->integer('order_id');
            $table->integer('customer_id');
            $table->enum('order_source', array('warehouse', 'supplier'));
            $table->integer('created_by');
            $table->integer('delivery_location_id');
            $table->string('other_location');
            $table->string('vat_percentage');
            $table->integer('estimate_price');
            $table->string('estimated_delivery_date');
            $table->string('expected_delivery_date');
            $table->string('remarks');
            $table->string('serial_no');
            $table->string('vehicle_number');
            $table->string('driver_name');
            $table->string('driver_contact_no', 20);
            $table->enum('order_status', array('pending', 'completed', 'cancelled'));
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('delivery_order');
    }

}
