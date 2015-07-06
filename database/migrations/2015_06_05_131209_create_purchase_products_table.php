<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseProductsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('all_purchase_products', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_order_id');
            $table->enum('order_type', array('purchase_order', 'purchase_advice', 'purchase_challan'));
            $table->integer('product_category_id');
            $table->integer('unit_id');
            $table->integer('quantity');
            $table->integer('actual_pieces');
            $table->integer('price');
            $table->integer('present_shipping');
            $table->text('remarks');
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
        Schema::drop('all_purchase_products');
    }

}
