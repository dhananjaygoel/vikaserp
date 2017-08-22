<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveryChallanLabours03 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('delivery_challan_labours', function(Blueprint $table) {
             $table->string('total_qty')->after('type')->comment('Total Quantity')->default('0'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('delivery_challan_labours', function(Blueprint $table) {
            $table->dropColumn('total_qty'); 
        });
    }

}
