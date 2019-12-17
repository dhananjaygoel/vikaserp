<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAllOrderProductsIndex04 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('all_order_products', function(Blueprint $table) {
////			 $table->index('all_order_products.from');
//            DB::statement(DB::raw("ALTER TABLE  `all_order_products` ADD INDEX (`from`) ;"));
////            $table->index('parent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('all_order_products', function(Blueprint $table) {
////			 $table->dropIndex('from');
//            DB::statement(DB::raw("ALTER TABLE  `all_order_products` DROP INDEX  `from` ;"));
////            $table->dropIndex('all_order_products_parent_index');
        });
    }

}
