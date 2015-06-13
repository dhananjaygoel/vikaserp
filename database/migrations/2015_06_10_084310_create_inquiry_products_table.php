<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiryProductsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('inquiry_products', function(Blueprint $table) {
            $table->increments('id')->comment('Primary key');
            $table->integer('inquiry_id')->comment('Inquiry id from inquiry table');
            $table->integer('product_category_id')->comment('Product category id from product category table');
            $table->integer('unit_id')->comment('Unit id from units table');
            $table->integer('quantity')->comment('Product quantity');
            $table->integer('price')->comment('Product price');
            $table->text('remarks')->comment('Contains remark for the product');
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
        Schema::drop('inquiry_products');
    }

}
