<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInquiryAlterLocationField extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('inquiry', function(Blueprint $table) {
            $table->dropColumn('other_location_difference');
        });
        Schema::table('inquiry', function(Blueprint $table) {
            $table->string('location_difference')->after('other_location')->comment('Contains delivery location difference');
        });
        Schema::table('orders', function(Blueprint $table) {
            $table->dropColumn('other_location_difference');
        });
        Schema::table('orders', function(Blueprint $table) {
            $table->string('location_difference')->after('other_location')->comment('Contains delivery location difference');
        });
        Schema::table('delivery_order', function(Blueprint $table) {
            $table->dropColumn('other_location_difference');
        });
        Schema::table('delivery_order', function(Blueprint $table) {
            $table->string('location_difference')->after('other_location')->comment('Contains delivery location difference');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('inquiry', function($table) {
            $table->dropColumn('location_difference');
        });
        Schema::table('inquiry', function($table) {
            $table->string('other_location_difference')->after('other_location')->comment('Contains other delivery location difference');
        });
        Schema::table('orders', function($table) {
            $table->dropColumn('location_difference');
        });
        Schema::table('orders', function($table) {
            $table->string('other_location_difference')->after('other_location')->comment('Contains other delivery location difference');
        });
        Schema::table('delivery_order', function($table) {
            $table->dropColumn('location_difference');
        });
        Schema::table('delivery_order', function($table) {
            $table->string('other_location_difference')->after('other_location')->comment('Contains other delivery location difference');
        });
    }

}
