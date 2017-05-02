<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCustomerReceipt01 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
            Schema::table('customer_receipts', function(Blueprint $table) {
                if (Schema::hasColumn('customer_receipts','settled_amount')) {
                    $table->dropColumn('settled_amount');
                }                                
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            Schema::table('customer_receipts', function($table) {
                $table->dropColumn('settled_amount');
            });
        }

}
