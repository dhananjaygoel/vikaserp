<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAllorderProductsAddParent extends Migration {

	/**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('all_order_products', function(Blueprint $table) {
            $table->integer('parent')->comment('Parent row id of same table');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('all_order_products', function($table) {
            $table->dropColumn('parent');
        });
    }

}
