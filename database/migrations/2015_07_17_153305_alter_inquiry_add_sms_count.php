<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInquiryAddSmsCount extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('inquiry', function(Blueprint $table) {
            $table->integer('sms_count')->after('inquiry_status')->comment('Track the sms count for an inquiry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('inquiry', function($table) {
            $table->dropColumn('sms_count');
        });
    }

}
