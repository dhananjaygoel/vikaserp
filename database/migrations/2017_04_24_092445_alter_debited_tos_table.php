<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDebitedTosTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('debited_tos', function(Blueprint $table) {
            if (Schema::hasColumn('debited_tos', 'user_id')) {
                $table->renameColumn('debited_by', 'debited_to');
            }
            $table->string('debited_to_type')->after('debited_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('debited_tos', function(Blueprint $table) {
            $table->dropColumn('debited_to_type');
            $table->renameColumn('debited_to', 'debited_by');
        });
    }

}
