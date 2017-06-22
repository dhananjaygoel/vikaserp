<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLoadedBies02 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('loaded_bies', function(Blueprint $table) {
            $table->string('type')->after('password')->comment('type:sale, purchase, both')->default('sale');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('loaded_bies', function(Blueprint $table) {
            $table->dropColumn('type');
        });
    }

}
