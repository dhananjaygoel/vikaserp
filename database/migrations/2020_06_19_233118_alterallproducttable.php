<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Alterallproducttable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('all_order_products', function(Blueprint $table) {
            DB::select(DB::raw('ALTER TABLE `all_order_products` CHANGE COLUMN `actual_pieces` `actual_pieces` INT NULL;'));
            DB::select(DB::raw('ALTER TABLE `all_order_products` CHANGE COLUMN `actual_quantity` `actual_quantity` decimal(8,2) NULL;'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
