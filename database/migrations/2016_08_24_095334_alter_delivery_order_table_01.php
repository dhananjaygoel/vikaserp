<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveryOrderTable01 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('delivery_order', function(Blueprint $table) {
            $table->boolean('flaged')->after('supplier_id')->comment('priority delivery order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('delivery_order', function($table) {
            $table->dropColumn('flaged');
        });
    }

}
