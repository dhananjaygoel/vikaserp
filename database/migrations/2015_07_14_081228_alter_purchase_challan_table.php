<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPurchaseChallanTable extends Migration {

    /**
     * Run the migrations.
     *sdsds
     * @return void
     */
    public function up() {
        Schema::table('purchase_challan', function(Blueprint $table) {
            $table->float('round_off')->after('unloading_charge');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('purchase_challan', function($table) {
            $table->dropColumn('round_off');
        });
    }

}
