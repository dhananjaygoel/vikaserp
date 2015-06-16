<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseCancelledTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('purchase_order_canceled', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_order_id');
            $table->enum('purchase_type', array('purchase_order', 'purchase_advice', 'purchase_challan'));
            $table->text('reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('purchase_order_canceled');
    }

}
