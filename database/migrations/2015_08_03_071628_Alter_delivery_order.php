<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveryOrder extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('delivery_order', function(Blueprint $table) {
            $table->integer('supplier_id')->comment('fullfilled by supplier id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('delivery_order', function($table) {
            $table->dropColumn('supplier_id');
        });
    }

}
