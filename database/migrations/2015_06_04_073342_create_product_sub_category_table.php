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
            $table->string('size');
            $table->integer('unit_id');
            $table->double('weight');
            $table->string('thickness');
            $table->string('standard_length');
            $table->string('difference', 100);
            $table->index('alias_name');
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
