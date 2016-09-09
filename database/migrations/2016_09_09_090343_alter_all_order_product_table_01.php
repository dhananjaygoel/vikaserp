<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAllOrderProductTable01 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('all_order_products', function(Blueprint $table) {
            $table->integer('app_product_id')->default(0)->after('price')->comment('Mobile application local id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('all_order_products', function($table) {
            $table->dropColumn('app_product_id');
        });
    }

}
