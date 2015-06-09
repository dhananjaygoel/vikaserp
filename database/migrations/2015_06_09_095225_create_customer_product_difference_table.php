<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerProductDifferenceTable extends Migration {

    public function up() {
        Schema::create('customer_product_difference', function(Blueprint $table) {
            $table->increments('id')->comment('Primary key of the table');
            $table->integer('product_category-id')->comment('Product category id');
            $table->integer('customer_id')->comment('Customer id');
            $table->string('difference_amount')->comment('Difference amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('customer_product_difference');
    }

}
