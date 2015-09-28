<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePriceColumnProductCategory extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
//        Schema::table('product_category', function($t) {
//            $t->decimal('price_new', 8, 2)->after('price');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
//        Schema::table('product_category', function($t) {
//            $t->dropColumn('price_new');
//        });
    }

}
