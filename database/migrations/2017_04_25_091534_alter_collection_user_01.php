<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollectionUser01 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('collection_user_location', function(Blueprint $table) {
            $table->integer('teritory_id')->after('location_id')->comment('territory id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('collection_user_location', function($table) {
            $table->dropColumn('teritory_id');
        });
    }

        
}
