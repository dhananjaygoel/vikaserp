<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInventory03 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('inventory', function(Blueprint $table) {
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE minimal minimal DECIMAL( 20, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE opening_qty opening_qty DECIMAL( 20, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE sales_challan_qty sales_challan_qty DECIMAL( 20, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE purchase_challan_qty purchase_challan_qty DECIMAL( 20, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE physical_closing_qty physical_closing_qty DECIMAL( 20, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE pending_sales_order_qty pending_sales_order_qty DECIMAL( 20, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE pending_delivery_order_qty pending_delivery_order_qty DECIMAL( 20, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE pending_purchase_order_qty pending_purchase_order_qty DECIMAL( 20, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE pending_purchase_advise_qty pending_purchase_advise_qty DECIMAL( 20, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE virtual_qty virtual_qty DECIMAL( 20, 2 ) NOT NULL ;"));
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('inventory', function(Blueprint $table) {
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE minimal minimal DECIMAL( 8, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE opening_qty opening_qty DECIMAL( 8, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE sales_challan_qty sales_challan_qty DECIMAL( 8, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE purchase_challan_qty purchase_challan_qty DECIMAL( 8, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE physical_closing_qty physical_closing_qty DECIMAL( 8, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE pending_sales_order_qty pending_sales_order_qty DECIMAL( 8, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE pending_delivery_order_qty pending_delivery_order_qty DECIMAL( 8, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE pending_purchase_order_qty pending_purchase_order_qty DECIMAL( 8, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE pending_purchase_advise_qty pending_purchase_advise_qty DECIMAL( 8, 2 ) NOT NULL ;"));
            DB::statement(DB::raw("ALTER TABLE  `inventory` CHANGE virtual_qty virtual_qty DECIMAL( 8, 2 ) NOT NULL ;"));
           
            
        });
    }

}
