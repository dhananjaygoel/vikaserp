<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveryOrder03 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('delivery_order', function(Blueprint $table) {
            $table->integer('empty_truck_weight')->default('0')->after('driver_contact_no');
            $table->integer('final_truck_weight')->default('0')->after('empty_truck_weight');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('delivery_order', function(Blueprint $table) {
            $table->dropColumn('empty_truck_weight');
            $table->dropColumn('final_truck_weight');
        });
    }

}
