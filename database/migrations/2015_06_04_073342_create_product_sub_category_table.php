<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSubCategoryTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('product_sub_category', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('product_category_id');
            $table->string('alias_name');
            $table->integer('size');
            $table->integer('unit_id');
            $table->integer('weight');
            $table->integer('thickness');
            $table->string('difference', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('product_sub_category');
    }

}
