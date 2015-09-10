<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamePriceNewToPriceProductCategoryclear extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('product_category', function($t) {
            $t->dropColumn('price');
        });
        Schema::table('product_category', function($t) {
            $t->renameColumn('price_new', 'price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
//
    }

}
