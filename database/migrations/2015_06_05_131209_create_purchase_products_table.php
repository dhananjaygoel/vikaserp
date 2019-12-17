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
            $table->integer('actual_pieces');
            $table->integer('present_shipping');
            $table->decimal('quantity', 8, 2);
            $table->decimal('price', 8, 2);
            $table->integer('parent')->comment('Parent row id of same table');
            $table->string('from')->comment('purchase from which module');
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
