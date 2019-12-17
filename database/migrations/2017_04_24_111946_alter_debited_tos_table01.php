<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDebitedTosTable01 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        if (!Schema::hasTable('debited_tos')) {
            Schema::table('debited_tos', function(Blueprint $table) {
                if (Schema::hasColumn('debited_tos', 'debited_by')) {
                    $table->dropColumn('debited_by');
                }
                $table->string('debited_to')->after('id');
                $table->string('debited_to_type')->after('debited_by');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        if (Schema::hasTable('debited_tos')) {
            Schema::table('debited_tos', function(Blueprint $table) {
                $table->dropColumn('debited_to');
                $table->dropColumn('debited_to_type');
                $table->string('debited_by')->after('id');
            });
        }
    }

}
