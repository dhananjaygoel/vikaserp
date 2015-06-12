<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('order', function(Blueprint $table) {
            $table->increments('id')->comment('primary key');
            $table->enum('order_source', array('warehouse', 'supplier'))->comment('order fulfilled through');
            $table->integer('customer_id')->comment('Customer associated with the order');
            $table->integer('created_by')->comment('Who created this order');
            $table->integer('delivery_location_id')->comment('Order delivery location');
            $table->int('vat_percent')->comment('Vat percent id applied');
            $table->integer('estimated_price')->comment('Estimated price of the order');
            $table->integer('estimated_delivery_date')->comment('Estimated delivery date');
            $table->string('expected_delivery_date')->comment('Expected delivery date');
            $table->string('remarks')->comment('Creator comment on the order');
            $table->enum('order_status', array('pending', 'completed', 'cancelled'))->comment('order status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('order');
    }

}
