<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColTcsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('tcs_applicable')->nullable()->after('vat_percentage');
            $table->float('tcs_percentage',8,6)->nullable()->after('tcs_applicable');
        });
        Schema::table('delivery_order', function (Blueprint $table) {
            $table->integer('tcs_applicable')->nullable()->after('vat_percentage');
            $table->float('tcs_percentage',8,6)->nullable()->after('tcs_applicable');
        });
        Schema::table('delivery_challan', function (Blueprint $table) {
            $table->integer('tcs_applicable')->nullable()->after('vat_percentage');
            $table->float('tcs_percentage',8,6)->nullable()->after('tcs_applicable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('tcs_applicable');
            $table->dropColumn('tcs_percentage');
        });
        Schema::table('delivery_order', function (Blueprint $table) {
            $table->dropColumn('tcs_applicable');
            $table->dropColumn('tcs_percentage');
        });
        Schema::table('delivery_challan', function (Blueprint $table) {
            $table->dropColumn('tcs_applicable');
            $table->dropColumn('tcs_percentage');
        });
    }
}
