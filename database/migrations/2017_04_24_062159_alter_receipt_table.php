<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReceiptTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
//        Schema::table('receipts', function(Blueprint $table) {
////            $table->dropColumn(array('user_id', 'settled_amount', 'total_amount', 'type_id', 'debited_to'));
////            $table->dropColumn(array('customer_id', 'settled_amount', 'total_amount', 'debited_by_type', 'debited_to'));
//            if (Schema::hasColumn('receipts', 'user_id')) {
//                $table->dropColumn('user_id');
//            }
//            if (Schema::hasColumn('receipts', 'settled_amount')) {
//                $table->dropColumn('settled_amount');
//            }
//            if (Schema::hasColumn('receipts', 'total_amount')) {
//                $table->dropColumn('total_amount');
//            }
//            if (Schema::hasColumn('receipts', 'type_id')) {
//                $table->dropColumn('type_id');
//            }
//            if (Schema::hasColumn('receipts', 'debited_to')) {
//                $table->dropColumn('debited_to');
//            }
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
//        Schema::table('receipts', function(Blueprint $table) {
//            $table->string('user_id')->after('id');	
//            $table->string('settled_amount')->after('user_id');	
//            $table->string('total_amount')->after('settled_amount');	
//            $table->string('type_id')->after('total_amount');	
//            $table->string('debited_to')->after('type_id');
//        });
    }

}
