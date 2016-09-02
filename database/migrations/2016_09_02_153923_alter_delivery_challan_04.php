<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveryChallan04 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('delivery_challan', function(Blueprint $table) {
            $table->integer('ref_delivery_challan_id')->after('remarks')->comment('Reference to Delievry Challan Id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('delivery_challan', function($table) {
            $table->dropColumn('ref_delivery_challan_id');
        });
    }

}
