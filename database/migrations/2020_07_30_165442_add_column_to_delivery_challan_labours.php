<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToDeliveryChallanLabours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_challan_labours', function (Blueprint $table) {
            $table->integer('truck_weight_id')->nullable()->after('delivery_challan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_challan_labours', function (Blueprint $table) {
            $table->dropColumn('truck_weight_id');
        });
    }
}
