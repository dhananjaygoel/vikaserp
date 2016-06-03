<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOpeningStockDateToInventoryTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('inventory', function(Blueprint $table) {
            $table->timestamps('opening_qty_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('inventory', function($table) {
            $table->dropColumn('opening_qty_date');
        });
    }

}
