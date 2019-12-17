<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCustomerReceipt02 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
            Schema::table('customer_receipts', function(Blueprint $table) {                
                
                $table->string('settled_amount')->after('customer_id')->comment('Settled amount');
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
