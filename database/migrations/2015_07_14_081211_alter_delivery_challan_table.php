<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveryChallanTable extends Migration {

    /**
     * Run the migrations.
     *dsds
     * @return void
     */
    public function up() {
        Schema::table('delivery_challan', function(Blueprint $table) {
            $table->float('round_off')->after('loading_charge');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('delivery_challan', function($table) {
            $table->dropColumn('round_off');
        });
    }

}
