<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAllPurchaseProductTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
           Schema::table('all_purchase_products', function($table) {  
            $table->dropColumn('quantity');
            $table->dropColumn('price');
        });
        Schema::table('all_purchase_products', function(Blueprint $table) {
            $table->string('from')->comment('purchase from which module');
            $table->float('quantity');
            $table->float('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('all_purchase_products', function($table) {
            $table->dropColumn('from');
            $table->dropColumn('quantity');
            $table->dropColumn('price');
        });
    }

}
