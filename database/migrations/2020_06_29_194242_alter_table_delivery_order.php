<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDeliveryOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_order', function(Blueprint $table) {
            $table->integer('printed_by')->nullable()->after('del_boy');
            $table->timestamp('print_time')->nullable()->after('printed_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_order', function(Blueprint $table) {
            $table->dropColumn('printed_by');
            $table->dropColumn('print_time');
        });
    }
}
