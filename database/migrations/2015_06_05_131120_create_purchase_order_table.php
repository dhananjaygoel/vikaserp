<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('purchase_order', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('supplier_id');
            $table->integer('created_by');
            $table->integer('is_view_all');
            $table->integer('delivery_location_id');
            $table->integer('order_for');
            $table->string('vat_percentage');
            $table->integer('total_price');
            $table->date('expected_delivery_date');
            $table->text('remarks');
            $table->enum('order_status', array('pending', 'completed', 'canceled'));            
            $table->string('other_location')->comment('Contains other delivery location');
            $table->string('other_location_difference')->comment('Contains other delivery location difference');            
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
        Schema::drop('purchase_order');
    }

}
