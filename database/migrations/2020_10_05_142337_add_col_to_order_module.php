<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToOrderModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function(Blueprint $table) {
            DB::select(DB::raw('ALTER TABLE `orders` CHANGE COLUMN `tcs_percentage` `tcs_percentage` FLOAT(8,6) NULL;'));
        });
        Schema::table('delivery_order', function(Blueprint $table) {
            DB::select(DB::raw('ALTER TABLE `delivery_order` CHANGE COLUMN `tcs_percentage` `tcs_percentage` FLOAT(8,6) NULL;'));
        });
        Schema::table('delivery_challan', function(Blueprint $table) {
            DB::select(DB::raw('ALTER TABLE `delivery_challan` CHANGE COLUMN `tcs_percentage` `tcs_percentage` FLOAT(8,6) NULL;'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_module', function (Blueprint $table) {
            //
        });
    }
}
