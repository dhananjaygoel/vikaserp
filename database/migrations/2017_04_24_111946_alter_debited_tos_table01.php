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
        Schema::table('debited_tos', function(Blueprint $table) {
            if (Schema::hasColumn('debited_tos', 'debited_by')) {
                $table->dropColumn('debited_by');
            }
            $table->string('debited_to')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }

}
