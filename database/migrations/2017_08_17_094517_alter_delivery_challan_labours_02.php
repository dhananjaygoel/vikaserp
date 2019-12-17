<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveryChallanLabours02 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('delivery_challan_labours', function(Blueprint $table) {
            $table->string('product_type_id',20)->after('type')->comment('1:Pipe, 2:Structure')->default('0')->index('product_type_id'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('delivery_challan_labours', function(Blueprint $table) {
            $table->dropColumn('product_type_id'); 
        });
    }

}
