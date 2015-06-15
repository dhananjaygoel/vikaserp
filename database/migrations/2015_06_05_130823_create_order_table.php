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
        Schema::create('orders', function(Blueprint $table) {
            $table->increments('id')->comment('primary key')->unsigned();
            $table->enum('order_source', array('warehouse', 'supplier'))->comment('order fulfilled through');
            $table->integer('supplier_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->integer('created_by')->unsigned();
            $table->integer('delivery_location_id')->unsigned();
            $table->string('vat_percentage', 100)->comment('Vat percent id applied');
            $table->date('estimated_delivery_date')->comment('Estimated delivery date');
            $table->date('expected_delivery_date')->comment('Expected delivery date');
            $table->string('remarks')->comment('Creator comment on the order');
            $table->enum('order_status', array('pending', 'completed', 'cancelled'))->comment('order status');
            $table->string('other_location')->comment('Contains other delivery location');
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
            Schema::drop('orders');
    }

}
