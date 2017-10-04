<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPurchaseOrderTable01 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
            Schema::table('purchase_order', function(Blueprint $table) {
                $table->string('discount_type')->after('order_status')->comment('discount type');
                $table->string('discount_unit')->after('discount_type')->comment('discount unit');
                $table->double('discount')->after('discount_unit')->comment('discount');
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            Schema::table('purchase_order', function($table) {
                $table->dropColumn('discount_type');
                $table->dropColumn('discount_unit');
                $table->dropColumn('discount');
            });
        }

}
