<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPurchaseChallan02 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement(DB::raw("ALTER TABLE purchase_challan CHANGE COLUMN grand_total grand_total decimal(12,2) NOT NULL;"));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement(DB::raw("ALTER TABLE purchase_challan CHANGE COLUMN grand_total grand_total decimal(8,2) NOT NULL;"));
    }

}
