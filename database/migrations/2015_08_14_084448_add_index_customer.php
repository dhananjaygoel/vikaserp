<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexCustomer extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('customers', function($t) {
            $t->index('owner_name');
            $t->index('tally_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
//        Schema::table('customers', function($t) {
//            $t->dropIndex('owner_name');
//            $t->dropIndex('tally_name');
//        });
    }

}
