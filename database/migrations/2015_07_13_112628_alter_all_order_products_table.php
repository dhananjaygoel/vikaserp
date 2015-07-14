<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAllOrderProductsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('all_order_products', function(Blueprint $table) {
            $table->dropColumn('actual_quantity');
            $table->dropColumn('quantity');
            $table->dropColumn('price');
            $table->dropColumn('present_shipping');
        });
        Schema::table('all_order_products', function(Blueprint $table) {
            $table->float('actual_quantity');
            $table->float('quantity');
            $table->float('price');
            $table->float('present_shipping');
            $table->string('from')->comment('order from which module');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::table('all_order_products', function($table) {
            $table->dropColumn('actual_quantity');
            $table->dropColumn('quantity');
            $table->dropColumn('price');
            $table->dropColumn('present_shipping');
            $table->dropColumn('from');
        });
        Schema::table('all_order_products', function($table) {
            $table->integer('actual_quantity')->comment('Actual Quantity for product');
            $table->integer('quantity')->comment('Quantity for product');
            $table->integer('price')->comment('Price for product');
            $table->integer('present_shipping')->comment('Present shipping');
            
        });
    }

}
