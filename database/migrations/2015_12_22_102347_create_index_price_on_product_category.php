<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexPriceOnProductProductCategory extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('product_category', function(Blueprint $table) {
            $table->index('prodyct_category_price_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('product_category', function(Blueprint $table) {
            $table->dropIndex('prodyct_category_price_index');
        });
    }

}
