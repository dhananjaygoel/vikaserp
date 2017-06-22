<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLabours02 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('labours', function(Blueprint $table) {
            $table->string('type')->after('password')->comment('type:sale, purchase, both')->default('sale');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('labours', function(Blueprint $table) {
            $table->dropColumn('type');
        });
    }

}
