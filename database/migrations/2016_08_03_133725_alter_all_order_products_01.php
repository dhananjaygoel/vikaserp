<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAllOrderProducts01 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('all_order_products', function(Blueprint $table) {
            $table->decimal('vat_percentage', 8, 2)->after('price')->comment('Vat percentage for product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('all_order_products', function($table) {
            $table->dropColumn('vat_percentage');
        });
    }

}
