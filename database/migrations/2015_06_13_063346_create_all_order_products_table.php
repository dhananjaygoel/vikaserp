<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllOrderProductsTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('all_order_products', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->comment('order id for product list');
            $table->enum('order_type', array('order', 'delivery_order', 'delivery_challan'))->comment('order type');
            $table->integer('product_category_id')->comment('Product category');
            $table->integer('unit_id')->comment('Unit for product');
            $table->integer('actual_pieces')->comment('Actual pieces of product');
            $table->decimal('actual_quantity', 8, 2)->comment('Actual Quantity for product');
            $table->decimal('quantity', 8, 2)->comment('Quantity for product');
            $table->decimal('price', 8, 2)->comment('Price for product');
            $table->decimal('present_shipping', 8, 2)->comment('Present shipping');
            $table->string('from')->comment('order from which module');
            $table->integer('parent')->comment('Parent row id of same table');
            $table->text('remarks')->comment('Individual Remarks for product');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::drop('all_order_products');
    }

}
