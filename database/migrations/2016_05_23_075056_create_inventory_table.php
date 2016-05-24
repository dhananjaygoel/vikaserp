<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('inventory', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('product_sub_category_id');
            $table->double('opening_qty', 8, 2);
            $table->double('sales_challan_qty', 8, 2);
            $table->double('purchase_challan_qty', 8, 2);
            $table->double('physical_closing_qty', 8, 2);
            $table->double('pending_sales_order_qty', 8, 2);
            $table->double('pending_delivery_order_qty', 8, 2);
            $table->double('pending_purchase_order_qty', 8, 2);
            $table->double('pending_purchase_advise_qty', 8, 2);
            $table->double('virtual_qty', 8, 2);
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
        Schema::drop('inventory');
    }

}
