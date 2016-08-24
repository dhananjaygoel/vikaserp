<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveryChallan01 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('delivery_challan', function(Blueprint $table) {
            $table->boolean('loading_vat_percentage')->after('grand_price')->comment('loading vat percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('delivery_challan', function($table) {
            $table->dropColumn('loading_vat_percentage');
        });
    }

}
