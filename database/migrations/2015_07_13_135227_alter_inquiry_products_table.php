<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInquiryProductsTable extends Migration {

	/**
     * Run the migrations.
     *sdsdsd
     * @return void
     */
    public function up() {
        Schema::table('inquiry_products', function(Blueprint $table) {
            $table->dropColumn('price');
        });
        Schema::table('inquiry_products', function(Blueprint $table) {
            $table->float('price')->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        Schema::table('inquiry_products', function($table) {
            $table->dropColumn('price');
        });
        Schema::table('inquiry_products', function($table) {
            $table->integer('price')->comment('Price for product')->after('quantity');
        });
    }

}
