<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveryChallanLabours01 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('delivery_challan_labours', function(Blueprint $table) {
            $table->string('type')->after('labours_id')->comment('type:sale, purchase')->default('sale'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('delivery_challan_labours', function(Blueprint $table) {
            $table->dropColumn('type'); 
        });
    }

}
